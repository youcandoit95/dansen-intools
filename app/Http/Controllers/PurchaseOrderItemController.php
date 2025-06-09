<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    public function create(PurchaseOrder $purchase_order)
    {
        $products = Product::orderBy('nama')->get();
        return view('purchase-order-item.create', compact('purchase_order', 'products'));
    }

    public function store(Request $request, PurchaseOrder $purchase_order)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.catatan' => 'nullable|string',
        ]);

        foreach ($validated['items'] as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchase_order->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'catatan' => $item['catatan'] ?? '',
                'created_by' => session('user_id'),
            ]);
        }

        return redirect()->route('purchase-order.index')->with('success', 'Item PO berhasil ditambahkan.');
    }
}
