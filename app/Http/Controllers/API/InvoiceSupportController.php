<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerPrice;
use App\Models\Stok;
use Illuminate\Http\Request;
use App\Models\ProductPrice;
use App\Models\SellPriceSetting;
use App\Models\Invoice;

class InvoiceSupportController extends Controller
{
    /**
     * Ambil stok berdasarkan produk.
     */
    public function stokByProduct($product_id)
    {
        $stok = Stok::where('product_id', $product_id)
            ->where('temp', false)
            ->whereNull('invoice_id')
            ->where('cabang_id', session('cabang_id'))
            ->orderBy('created_at', 'desc')
            ->get(['id', 'barcode_stok', 'berat_kg', 'kategori']);

        return response()->json($stok);
    }

    /**
     * Ambil harga customer berdasarkan produk.
     */
    public function customerPrice(Request $request, $customer_id, $product_id)
    {
        $platform = $request->query('platform', 'online'); // default ke offline jika tidak dikirim
        $invoice_id = $request->query('invoice_id'); // opsional, jika ingin cek invoice yang sedang dibuat

        $price = CustomerPrice::where('customer_id', $customer_id)
            ->where('product_id', $product_id)
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->first();

        if ($price) {
            return response()->json([
                'harga' => $price->harga_jual,
                'sumber' => 'customer_price'
            ]);
        }

        // Fallback: Ambil harga supplier tertinggi dari product price
        $productPrice = ProductPrice::where('product_id', $product_id)
            ->orderByDesc('harga')
            ->first();

        if (!$productPrice) {
            return response()->json([
                'harga' => null,
                'sumber' => 'not_found'
            ]);
        }

        // Ambil setting persentase aktif terakhir
        $setting = SellPriceSetting::latest('id')->whereNull('deleted_at')->first();
        $percent = 0;

        if ($setting) {
            $percent = $platform === 'offline'
                ? ($setting->offline ?? 0)
                : ($setting->online ?? 0);
        }

        $harga = (int) ceil($productPrice->harga * (1 + $percent / 100));

        return response()->json([
            'harga' => $harga,
            'sumber' => 'product_price_fallback',
            'platform' => $platform,
            'persentase' => $percent
        ]);
    }

    public function productDetail($id)
    {
        $product = \App\Models\Product::with([
            'mbs',
            'bagianDaging',
            'productImages',
            'productPrices'
        ])->findOrFail($id);

        $maxHarga = $product->productPrices->max('harga') ?? 0;

        $setting = SellPriceSetting::latest()->first();
        $hargaPersent = [];

        foreach (['online', 'offline', 'reseller', 'resto', 'bottom'] as $key) {
            $persen = $setting?->$key ?? 0;
            $hasil = ceil($maxHarga * (1 + $persen / 100));
            $hargaPersent[$key] = max($maxHarga + 5000, $hasil);
        }

        return response()->json([
            'nama' => $product->nama,
            'kategori' => $product->kategori_label,
            'brand' => $product->brand_label,
            'mbs' => $product->mbs ? "{$product->mbs->a_grade} - {$product->mbs->bms}" : '-',
            'bagian' => $product->bagianDaging->nama ?? '-',
            'deskripsi' => $product->deskripsi ?? '-',
            'images' => $product->productImages->map(function ($img) {
                return asset('storage/' . ltrim($img->path, '/'));
            })->toArray(),

            'hargaPersent' => $hargaPersent
        ]);
    }
}
