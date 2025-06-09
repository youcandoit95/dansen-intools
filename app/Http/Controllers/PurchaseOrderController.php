<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\ProductPrice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $data = PurchaseOrder::with('supplier')
            ->where('cabang_id', session('cabang_id'))
            ->latest()
            ->get();
        $activeMenu = 'purchase-order';
        return view('purchase-order.index', compact('data', 'activeMenu'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('nama')->get();
        $activeMenu = 'purchase-order';

        return view('purchase-order.create', compact('suppliers', 'products', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_po' => 'required|unique:purchase_orders',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'tanggal_permintaan_dikirim' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        $po = PurchaseOrder::create([
            'no_po' => $request->no_po,
            'supplier_id' => $request->supplier_id,
            'tanggal' => $request->tanggal,
            'tanggal_permintaan_dikirim' => $request->tanggal_permintaan_dikirim,
            'catatan' => $request->catatan,
            'cabang_id' => session('cabang_id'), // jika digunakan
            'created_by' => session('user_id'),
        ]);

        return redirect()->route('purchase-order.edit', $po->id)
                 ->with('success', 'PO berhasil disimpan. Silakan tambahkan item.');

    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $activeMenu = 'purchase-order';
        return view('purchase-order.show', compact('purchaseOrder', 'activeMenu'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = ProductPrice::with('product')
        ->where('supplier_id', $purchaseOrder->supplier_id)
        ->orderBy('product_id')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->product_id,
                'nama' => $item->product->nama,
                'harga_beli' => $item->harga, // atau ->harga_beli jika itu nama field-nya
            ];
        });
        $activeMenu = 'purchase-order';

        return view('purchase-order.edit', compact('purchaseOrder', 'suppliers', 'products', 'activeMenu'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'tanggal_permintaan_dikirim' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        $purchaseOrder->update([
            'supplier_id' => $request->supplier_id,
            'tanggal' => $request->tanggal,
            'tanggal_permintaan_dikirim' => $request->tanggal_permintaan_dikirim,
            'catatan' => $request->catatan,
            'updated_by' => session('user_id'),
        ]);

        return redirect()->route('purchase-order.edit', $purchaseOrder->id)
                        ->with('success', 'PO berhasil diperbarui.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['deleted_by' => session('user_id')]);
        $purchaseOrder->delete();

        return back()->with('success', 'Purchase Order berhasil dihapus.');
    }
}
