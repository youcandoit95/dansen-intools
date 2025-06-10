<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'harga_beli' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        // Ambil harga numerik dari input 'harga_beli' yang berbentuk rupiah format
        $hargaBeli = (int) preg_replace('/\D/', '', $request->harga_beli);

        $purchaseOrder->items()->create([
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'harga_beli' => $hargaBeli,
            'catatan' => $request->catatan,
            'created_by'   => session('user_id'),
        ]);

        return redirect()->route('purchase-order.edit', $purchaseOrder->id)
                        ->with('success', 'Item berhasil ditambahkan ke PO.');
    }

    public function destroy($id)
    {
        $item = PurchaseOrderItem::findOrFail($id);
        $poId = $item->purchase_order_id;
        $item->deleted_by = session('user_id');
        $item->save();
        $item->delete();

        return redirect()->route('purchase-order.edit', $poId)
                        ->with('success', 'Item berhasil dihapus.');
    }
}
