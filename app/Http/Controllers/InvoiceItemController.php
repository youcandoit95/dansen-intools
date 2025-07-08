<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Stok;
use App\Models\CustomerPrice;
use Illuminate\Http\Request;
use App\Models\SellPriceSetting;
use App\Models\ProductPrice;

class InvoiceItemController extends Controller
{


public function store(Request $request)
{
    $validated = $request->validate([
        'inv_id'     => 'required|exists:invoices,id',
        'product_id' => 'required|exists:products,id',
        'stok_id'    => 'required|exists:stok,id',
        'qty_out'    => 'required|numeric|min:0.001',
        'sell_price' => 'required|integer|min:0',
        'note'       => 'nullable|string',
    ]);

    $qty_outbound = (float) $validated['qty_out'];
    $sell_price   = (int) $validated['sell_price'];

    // Ambil data stok
    $stok = Stok::findOrFail($validated['stok_id']);
    $qty_inbound    = (float) $stok->berat_kg;
    // Ambil harga beli dari tabel product_prices
$purchasePriceQuery = ProductPrice::where('product_id', $validated['product_id'])
    ->whereNull('deleted_at');

if ($stok->supplier_id ?? false) {
    $purchasePriceQuery->where('supplier_id', $stok->supplier_id);
}

$productPrice = $purchasePriceQuery->first();

if (!$productPrice) {
    return redirect()->back()->withErrors(['product_id' => 'Harga beli tidak ditemukan untuk produk ini.']);
}

$purchase_price = (int) $productPrice->harga;

    // Ambil data invoice (untuk ambil customer_id)
    $invoice = Invoice::findOrFail($validated['inv_id']);
    $customer_id = $invoice->customer_id;

    // Validasi: outbound tidak boleh lebih besar dari stok masuk
    if ($qty_outbound > $qty_inbound) {
        return redirect()->back()->withErrors(['qty_out' => 'Qty outbound tidak boleh melebihi stok masuk.']);
    }

    // Hitung waste
    $waste_kg     = max(0, $qty_inbound - $qty_outbound);
    $waste_amount = (int) ceil($waste_kg * $purchase_price);

    // Hitung total
    $total_purchase_price = (int) ceil($qty_outbound * $purchase_price);
    $total_sell_price     = (int) ceil($qty_outbound * $sell_price);
    $profit_gross         = $sell_price - $purchase_price;
    $total_profit_gross   = $total_sell_price - $total_purchase_price;

    // Ambil komisi dari tabel customer_prices
    $komisi_sales = 0;
    $customer_price = CustomerPrice::where('customer_id', $customer_id)
        ->where('product_id', $validated['product_id'])
        ->whereNull('deleted_at')
        ->first();

    if ($customer_price && $customer_price->komisi > 0) {
        $komisi_sales = (int) $customer_price->komisi;
    }

    $total_komisi_sales = (int) ceil($komisi_sales * $qty_outbound);

    // Hitung harga berdasarkan konfigurasi persentase
    $setting = SellPriceSetting::latest()->first();
    $hargaPersent = [];
    foreach (['online', 'offline', 'reseller', 'resto', 'bottom'] as $key) {
        $persen = $setting?->$key ?? 0;
        $hasil = ceil($purchase_price * (1 + $persen / 100));
        $hargaPersent[$key] = max($purchase_price + 5000, $hasil);
    }

    InvoiceItem::create([
        'inv_id'                   => $validated['inv_id'],
        'product_id'              => $validated['product_id'],
        'stok_id'                 => $validated['stok_id'],
        'qty_outbound'            => $qty_outbound,
        'waste_kg'                => $waste_kg,
        'waste_amount'            => $waste_amount,
        'purchase_price'          => $purchase_price,
        'sell_price'              => $sell_price,
        'total_purchase_price'    => $total_purchase_price,
        'total_sell_price'        => $total_sell_price,
        'profit_gross'            => $profit_gross,
        'total_profit_gross'      => $total_profit_gross,
        'ss_online_sell_price'    => $hargaPersent['online'],
        'ss_offline_sell_price'   => $hargaPersent['offline'],
        'ss_reseller_sell_price'  => $hargaPersent['reseller'],
        'ss_resto_sell_price'     => $hargaPersent['resto'],
        'ss_bottom_sell_price'    => $hargaPersent['bottom'],
        'ss_komisi_sales'         => $komisi_sales,
        'total_komisi_sales'      => $total_komisi_sales,
        'note'                    => $validated['note'],
        'created_by'              => session('user_id'),
    ]);

    return redirect()->back()->with('success', 'Item berhasil ditambahkan ke invoice.');
}
}
