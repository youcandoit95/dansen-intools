<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DefaultSellPrice;
use Illuminate\Http\Request;

class DefaultSellPriceController extends Controller
{
    public function index()
    {
        $defaultPrices = DefaultSellPrice::with('product')->latest()->paginate(20);

        return view('default_sell_price.index', compact('defaultPrices'));
    }

    public function create()
    {
        $products = Product::with([
                        'productPrices.supplier', // untuk harga dan nama supplier
                        'mbs',
                        'bagianDaging'
                    ])->get();

        return view('default_sell_price.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'online_sell_price' => 'required|string',
            'offline_sell_price' => 'required|string',
            'reseller_sell_price' => 'required|string',
            'resto_sell_price' => 'required|string',
            'bottom_sell_price' => 'required|string',
        ]);

        DefaultSellPrice::create([
            'product_id' => $validated['product_id'],
            'online_sell_price' => $this->parseRupiah($validated['online_sell_price']),
            'offline_sell_price' => $this->parseRupiah($validated['offline_sell_price']),
            'reseller_sell_price' => $this->parseRupiah($validated['reseller_sell_price']),
            'resto_sell_price' => $this->parseRupiah($validated['resto_sell_price']),
            'bottom_sell_price' => $this->parseRupiah($validated['bottom_sell_price']),
        ]);

        return redirect()->route('default-sell-prices.index')->with('success', 'Harga default berhasil ditambahkan.');
    }

    public function edit(DefaultSellPrice $defaultSellPrice)
    {
        $products = Product::with(['latest_price', 'mbs', 'bagianDaging'])->get();

        return view('default_sell_price.edit', [
            'defaultSellPrice' => $defaultSellPrice,
            'products' => $products,
        ]);
    }

    public function update(Request $request, DefaultSellPrice $defaultSellPrice)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'online_sell_price' => 'required|string',
            'offline_sell_price' => 'required|string',
            'reseller_sell_price' => 'required|string',
            'resto_sell_price' => 'required|string',
            'bottom_sell_price' => 'required|string',
        ]);

        $defaultSellPrice->update([
            'product_id' => $validated['product_id'],
            'online_sell_price' => $this->parseRupiah($validated['online_sell_price']),
            'offline_sell_price' => $this->parseRupiah($validated['offline_sell_price']),
            'reseller_sell_price' => $this->parseRupiah($validated['reseller_sell_price']),
            'resto_sell_price' => $this->parseRupiah($validated['resto_sell_price']),
            'bottom_sell_price' => $this->parseRupiah($validated['bottom_sell_price']),
        ]);

        return redirect()->route('default-sell-prices.index')->with('success', 'Harga default berhasil diperbarui.');
    }

    public function destroy(DefaultSellPrice $defaultSellPrice)
    {
        $defaultSellPrice->delete();

        return redirect()->route('default-sell-prices.index')->with('success', 'Harga default berhasil dihapus.');
    }

    /**
     * Convert format input rupiah menjadi integer.
     */
    private function parseRupiah($value)
    {
        return (int) str_replace(['Rp', '.', ','], '', $value);
    }
}
