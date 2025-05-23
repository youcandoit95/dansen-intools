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
        $usedProductIds = DefaultSellPrice::whereNull('deleted_at')
            ->pluck('product_id')
            ->toArray();

        $products = Product::with([
                'productPrices.supplier', // untuk harga dan nama supplier
                'mbs',
                'bagianDaging'
            ])
            ->whereNotIn('id', $usedProductIds)
            ->orderBy('nama')
            ->get();

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

        $product = Product::with('productPrices')->findOrFail($request->product_id);

        $this->validateSellPrice($request, $product);

        DefaultSellPrice::create([
            'product_id' => $validated['product_id'],
            'online_sell_price' => $this->parseRupiah($validated['online_sell_price']),
            'offline_sell_price' => $this->parseRupiah($validated['offline_sell_price']),
            'reseller_sell_price' => $this->parseRupiah($validated['reseller_sell_price']),
            'resto_sell_price' => $this->parseRupiah($validated['resto_sell_price']),
            'bottom_sell_price' => $this->parseRupiah($validated['bottom_sell_price']),
        ]);

        return redirect()->route('default-sell-price.index')->with('success', 'Harga default berhasil ditambahkan.');
    }

    public function edit(DefaultSellPrice $defaultSellPrice)
    {
        $products = Product::with([
            'productPrices.supplier', // penting untuk tabel harga supplier
            'mbs',
            'bagianDaging'
        ])->get();

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

        $product = Product::with('productPrices')->findOrFail($request->product_id);

        $this->validateSellPrice($request, $product);

        return redirect()->route('default-sell-price.index')->with('success', 'Harga default berhasil diperbarui.');
    }

    public function destroy(DefaultSellPrice $defaultSellPrice)
    {
        $defaultSellPrice->delete();

        return redirect()->route('default-sell-price.index')->with('success', 'Harga default berhasil dihapus.');
    }

    /**
     * Convert format input rupiah menjadi integer.
     */
    private function parseRupiah($value)
    {
        // Hapus semua karakter selain angka (contoh: "Rp 1.000.000" → "1000000")
        return (int) preg_replace('/[^\d]/', '', $value);
    }

    private function validateSellPrice(Request $request, Product $product)
    {
        $supplierPrices = $product->productPrices->pluck('harga')->filter()->toArray();
        $maxHarga = count($supplierPrices) ? max($supplierPrices) : 0;

        $minHarga = ceil($maxHarga * 1.05);
        $minHarga = max($minHarga, $maxHarga+5000); // minimal harus 5.000 walau hasil 5% < 5000


        $channels = [
            "online_sell_price" => "Online",
            "offline_sell_price" => "Offline",
            "reseller_sell_price" => "Reseller",
            "resto_sell_price" => "Resto",
            "bottom_sell_price" => "Harga Terendah",
        ];

        $errors = [];

        foreach ($channels as $field => $label) {
            $harga = preg_replace('/\D/', '', $request->input($field)) ?? 0;
            if ((int) $harga < $minHarga) {
                $errors[$field] = "Harga $label harus minimal 5% (".$minHarga.") lebih tinggi dari harga supplier tertinggi (≥ " . number_format($minHarga, 0, ',', '.') . ")";
            }
        }

        if (!empty($errors)) {
            throw \Illuminate\Validation\ValidationException::withMessages($errors);
        }
    }
}
