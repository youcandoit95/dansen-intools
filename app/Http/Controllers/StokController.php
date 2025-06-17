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
            'kategori'      => 'required|in:1,2,3,99',
            'berat_kg'      => 'required|numeric|min:0',
            'barcode_stok'  => 'required|string|unique:stok,barcode_stok',
        ]);

        $validated['created_by'] = session('user_id');

        $stok = Stok::create($validated);

        return response()->json([
            'message' => 'Data stok berhasil disimpan.',
            'data' => $stok,
        ]);
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

    // Hard delete stok jika inbound belum submitted
    public function delete($id)
    {
        $stok = Stok::with('inbound')->findOrFail($id);

        if ($stok->inbound && $stok->inbound->submitted_at !== null) {
            return response()->json([
                'message' => 'Stok tidak bisa dihapus karena sudah masuk inbound final.',
            ], 403);
        }

        // Hapus foto jika ada
        if ($stok->destroy_foto) {
            $fotoPath = str_replace('storage/', 'public/', $stok->destroy_foto);
            Storage::delete($fotoPath);
        }

        $stok->delete();

        return response()->json([
            'message' => 'Stok berhasil dihapus permanen.',
        ]);
    }
}
