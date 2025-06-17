<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inbound;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InboundController extends Controller
{
    public function index()
    {
        $data = Inbound::with('supplier')->latest()->get();
        $activeMenu = 'inbound';
        return view('inbound.index', compact('data', 'activeMenu'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $activeMenu = 'inbound';
        $purchaseOrders = PurchaseOrder::orderBy('no_po')->get();

        return view('inbound.create', compact('suppliers', 'activeMenu', 'purchaseOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat_jalan' => 'required|unique:inbounds,no_surat_jalan',
            'supplier_id' => 'required|exists:suppliers,id',
            'foto_surat_jalan_1' => 'required|image|max:3048',
            'foto_surat_jalan_2' => 'nullable|image|max:3048',
            'foto_surat_jalan_3' => 'nullable|image|max:3048',
        ]);

        // Simpan dulu tanpa foto
        $inbound = Inbound::create([
            'no_surat_jalan' => $request->no_surat_jalan,
            'supplier_id' => $request->supplier_id,
            'purchase_order_id' => $request->purchase_order_id,
            'created_by' => session('user_id'),
        ]);

        // Lalu update file
        foreach ([1, 2, 3] as $i) {
            $field = "foto_surat_jalan_$i";
            if ($request->hasFile($field)) {
                $inbound->$field = $request->file($field)->store("inbound/{$inbound->id}/surat-jalan");
            }
        }

        // ⬅️ SIMPAN perubahan path foto ke DB
        $inbound->save();

        return redirect()->route('inbound.edit', $inbound->id)->with('success', 'Inbound berhasil ditambahkan.');
    }

    public function edit(Inbound $inbound)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::orderBy('no_po')->get();
        $products = Product::orderBy('nama')->get();
        $activeMenu = 'inbound';

        // Load stok dan relasi product
        $inbound->load(['stok.product']);

        // Sort: 1) product name ASC, 2) berat_kg DESC
    $inbound->setRelation('stok', $inbound->stok
        ->sortByDesc('berat_kg') // sort berat_kg dulu (terbesar ke terkecil)
        ->sortBy(function ($stok) {
            return $stok->product->nama ?? '';
        })
        ->values()
    );

        return view('inbound.edit', compact('inbound', 'suppliers', 'products', 'activeMenu', 'purchaseOrders'));
    }


    public function update(Request $request, Inbound $inbound)
    {
        $validated = $request->validate([
            'no_surat_jalan' => 'required|unique:inbounds,no_surat_jalan,' . $inbound->id,
            'supplier_id' => 'required|exists:suppliers,id',
            'foto_surat_jalan_1' => 'nullable|image|max:2048',
            'foto_surat_jalan_2' => 'nullable|image|max:2048',
            'foto_surat_jalan_3' => 'nullable|image|max:2048',
        ]);

        $inbound->no_surat_jalan = $request->no_surat_jalan;
        $inbound->purchase_order_id = $request->purchase_order_id;
        $inbound->supplier_id = $request->supplier_id;
        $inbound->updated_by = session('user_id');

        // Update file jika diupload ulang
        foreach ([1, 2, 3] as $i) {
            $field = "foto_surat_jalan_$i";
            if ($request->hasFile($field)) {
                if ($inbound->$field) Storage::delete($inbound->$field);
                $inbound->$field = $request->file($field)->store("inbound/{$inbound->id}/surat-jalan");
            }
        }

        $inbound->save();

        return redirect()->route('inbound.edit', $inbound->id)->with('success', 'Inbound berhasil ditambahkan.');
    }

    public function submitInbound(Inbound $inbound)
    {
        if ($inbound->submitted_at) {
            return redirect()->back()->with('error', 'Inbound sudah disubmit sebelumnya.');
        }

        $inbound->submitted_at = now();
        $inbound->submitted_by = session('user_id');
        $inbound->save();

        return redirect()->route('inbound.index')->with('success', 'Inbound berhasil disubmit.');
    }

    public function hapusFoto(Inbound $inbound, $field)
    {
        if (!in_array($field, ['foto_surat_jalan_1', 'foto_surat_jalan_2', 'foto_surat_jalan_3'])) {
            abort(403, 'Field tidak valid.');
        }

        if ($inbound->$field && Storage::exists($inbound->$field)) {
            Storage::delete($inbound->$field);
        }

        $inbound->$field = null;
        $inbound->updated_by = session('user_id');
        $inbound->save();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
