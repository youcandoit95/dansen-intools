<?php

namespace App\Http\Controllers;

use App\Models\ProductPrice;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductPriceController extends Controller
{
    public function index()
    {

        $productPrices = ProductPrice::with(['product', 'supplier'])
                            ->whereHas('product')
                            ->whereHas('supplier')
                            ->latest()
                            ->get();

        return view('product_prices.index', compact('productPrices'));
    }

    public function create()
    {
        $products = Product::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('product_prices.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'harga' => 'required|string'
        ]);

        ProductPrice::abortIfDuplicate($data['product_id'], $data['supplier_id']);

        // Hapus format rupiah seperti "Rp 1.000.000" → "1000000"
        $data['harga'] = (int) preg_replace('/[^\d]/', '', $data['harga']);

        ProductPrice::create($data);

        return redirect()->route('product-prices.index')->with('success', 'Harga produk berhasil ditambahkan.');
    }

    public function edit(ProductPrice $productPrice)
    {
        $products = Product::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('product_prices.edit', compact('productPrice', 'products', 'suppliers'));
    }

    public function update(Request $request, ProductPrice $productPrice)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'harga' => 'required|string'
        ]);

        ProductPrice::abortIfDuplicate($data['product_id'], $data['supplier_id'], $productPrice->id);

        $data['harga'] = (int) preg_replace('/[^\d]/', '', $data['harga']);

        $productPrice->update($data);

        return redirect()->route('product-prices.index')->with('success', 'Harga produk berhasil diperbarui.');
    }

    public function destroy(ProductPrice $productPrice)
    {
        $productPrice->delete();
        return redirect()->route('product-prices.index')->with('success', 'Harga produk berhasil dihapus.');
    }
}
