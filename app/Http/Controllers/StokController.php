<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StokController extends Controller
{
    // Simpan stok baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'inbound_id'    => 'required|exists:inbounds,id',
            'kategori'      => 'required|in:1,2,3,99',
            'berat_kg'      => 'required|numeric|min:0',
        ]);

        $validated['created_by'] = session('user_id');
        $validated['cabang_id'] = session('cabang_id');
        $validated['temp'] = $request->boolean('temp', true);

        // Ambil harga dari product_prices sesuai supplier dan tidak soft deleted
        $inbound = \App\Models\Inbound::find($validated['inbound_id']); // pastikan inbound tersedia
        $supplierId = $inbound?->supplier_id;

        $hargaBeli = \App\Models\ProductPrice::where('product_id', $validated['product_id'])
            ->where('supplier_id', $supplierId)
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->value('harga');

        if (is_null($hargaBeli)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Harap hubungi admin, produk belum ada info harga.');
        }

        $validated['ss_harga_beli'] = $hargaBeli ?? 0;
        $validated['total_harga_beli'] = round($validated['berat_kg'] * $validated['ss_harga_beli']);

        // Generate barcode stok
        $validated['barcode_stok'] = 'STK' . strtoupper(session('cabang_initial')) . now()->format('YmdHis') . rand(1, 9) . ':' . $validated['product_id'];

        $stok = Stok::create($validated);

        return redirect()
            ->to(route('inbound.edit', $validated['inbound_id']) . '#tableStokMasuk')
            ->with('success', 'Stok berhasil ditambahkan.');
    }



    // Soft destroy stok
    public function destroy(Request $request, $id)
    {
        $stok = Stok::findOrFail($id);

        $validated = $request->validate([
            'destroy_type'   => 'required|in:1,2', // 1 = hilang, 2 = rusak
            'destroy_reason' => 'nullable|string',
            'destroy_foto'   => 'nullable|image|max:2048',
        ]);

        $dataUpdate = [
            'destroy_at'        => now(),
            'destroy_type'      => $validated['destroy_type'],
            'destroy_reason'    => $validated['destroy_reason'] ?? null,
            'destroy_by'        => session('user_id'),
        ];

        // Handle upload foto jika ada
        if ($request->hasFile('destroy_foto')) {
            $file = $request->file('destroy_foto');
            $filename = Str::random(16) . '.' . $file->getClientOriginalExtension();
            $path = "public/destroy/stok/{$stok->id}";
            $file->storeAs($path, $filename);

            $dataUpdate['destroy_foto'] = "storage/destroy/stok/{$stok->id}/{$filename}";
        }

        $stok->update($dataUpdate);

        return response()->json([
            'message' => 'Stok berhasil ditandai sebagai rusak/hilang.',
            'data' => $stok,
        ]);
    }

    public function delete($id)
    {
        $stok = Stok::with('inbound')->findOrFail($id);

        // Cek apakah inbound sudah final
        if ($stok->inbound && $stok->inbound->submitted_at !== null) {
            return redirect()
                ->route('inbound.edit', $stok->inbound_id)
                ->with('error', 'Stok tidak bisa dihapus karena sudah masuk inbound final.');
        }

        // Hapus foto jika ada
        if ($stok->destroy_foto) {
            $fotoPath = str_replace('storage/', 'public/', $stok->destroy_foto);
            Storage::delete($fotoPath);
        }

        $inboundId = $stok->inbound_id; // simpan sebelum hapus
        $stok->delete();

        return redirect()
            ->route('inbound.edit', $inboundId)
            ->with('success', 'Stok berhasil dihapus.');
    }
}
