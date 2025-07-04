<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerPrice;
use App\Models\Stok;
use Illuminate\Http\Request;

class InvoiceSupportController extends Controller
{
    /**
     * Ambil stok berdasarkan produk.
     */
    public function stokByProduct($product_id)
    {
       $stok = Stok::where('product_id', $product_id)
                    ->where('temp', false)
                    ->where('cabang_id', session('cabang_id'))
                    ->orderBy('created_at', 'desc')
                    ->get(['id', 'barcode_stok', 'berat_kg', 'kategori']);

        return response()->json($stok);
    }

    /**
     * Ambil harga customer berdasarkan produk.
     */
    public function customerPrice($customer_id, $product_id)
    {
        $price = CustomerPrice::where('customer_id', $customer_id)
            ->where('product_id', $product_id)
            ->orderByDesc('id')
            ->first();

        return response()->json([
            'harga' => $price?->harga ?? null,
        ]);
    }
}
