<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Stok;
use App\Models\CustomerPrice;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inv_id'     => 'required|exists:invoices,id',
            'product_id' => 'required|exists:products,id',
            'stok_id'    => 'nullable|exists:stok,id',
            'qty'        => 'required|integer|min:1',
            'sell_price' => 'required|integer|min:0',
            'note'       => 'nullable|string',
        ]);

        $validated['total_sell_price'] = $validated['qty'] * $validated['sell_price'];
        $validated['created_by'] = session('user_id');

        InvoiceItem::create($validated);

        return redirect()->back()->with('success', 'Item berhasil ditambahkan ke invoice.');
    }
}
