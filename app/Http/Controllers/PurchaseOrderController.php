<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $data = PurchaseOrder::with('supplier')->latest()->get();
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

        return redirect()->route('purchase-order-item.create', $po->id)
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
        $products = Product::orderBy('nama')->get();
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
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga_beli' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $purchaseOrder) {
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'tanggal_permintaan_dikirim' => $request->tanggal_permintaan_dikirim,
                'catatan' => $request->catatan,
                'updated_by' => session('user_id'),
            ]);

            $purchaseOrder->items()->delete();

            foreach ($request->items as $item) {
                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'harga_beli' => preg_replace('/[^0-9]/', '', $item['harga_beli']),
                ]);
            }
        });

        return redirect()->route('purchase-order.index')->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['deleted_by' => session('user_id')]);
        $purchaseOrder->delete();

        return back()->with('success', 'Purchase Order berhasil dihapus.');
    }
}
