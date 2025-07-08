<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesAgent;
use App\Models\CustomerPrice;
use Illuminate\Http\Request;

class CustomerPriceController extends Controller
{
    public function index()
    {
        $activeMenu = 'customer-prices';

        $customerPrices = CustomerPrice::with(['customer', 'product', 'salesAgent'])
            ->whereNull('deleted_at')
            ->paginate(20);

        return view('customer_prices.index', compact('customerPrices', 'activeMenu'));
    }

    public function create()
    {
        $activeMenu = 'customer-prices';

        $customers = Customer::orderBy('nama')->get();
        $products = Product::with([
            'productPrices.supplier',
            'mbs',
            'bagianDaging',
            'defaultSellPrice'
        ])->orderBy('nama')->get();

        $isEdit = false;

        return view('customer_prices.create', compact('customers', 'products', 'isEdit', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'product_id'    => 'required|exists:products,id',
            'harga_jual'    => 'required|string',
            'komisi_sales'  => 'nullable|string'
        ]);

        $data['harga_jual'] = (int) preg_replace('/[^\d]/', '', $data['harga_jual']);
        $data['komisi_sales'] = $request->filled('komisi_sales')
            ? (int) preg_replace('/[^\d]/', '', $data['komisi_sales'])
            : null;

        // Validasi harga minimum
        [$isValid, $hargaMinimal] = $this->validateMinimumPrice($data['product_id'], $data['harga_jual'], $data['komisi_sales']);
        if (!$isValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'harga_jual' => 'Harga minimal untuk produk ini adalah Rp ' . number_format($hargaMinimal, 0, ',', '.') . ' (harga supplier tertinggi + margin Rp 5.000)'
                ]);
        }

        // Cegah duplikat customer_id + product_id
        $exists = CustomerPrice::where('customer_id', $data['customer_id'])
            ->where('product_id', $data['product_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'product_id' => 'Harga untuk customer dan produk ini sudah ada.'
                ]);
        }

        CustomerPrice::create($data);

        return redirect()->route('customer-prices.index')->with('success', 'Harga customer berhasil disimpan.');
    }

    public function edit($id)
    {
        $activeMenu = 'customer-prices';

        $price = CustomerPrice::findOrFail($id);
        $customers = Customer::orderBy('nama')->get();
        $products = Product::with(['productPrices.supplier', 'defaultSellPrice'])->get();
        $isEdit = true;

        return view('customer_prices.edit', compact('price', 'customers', 'products', 'isEdit', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $price = CustomerPrice::findOrFail($id);

        $data = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'product_id'    => 'required|exists:products,id',
            'harga_jual'    => 'required|string',
            'komisi_sales'  => 'nullable|string'
        ]);

        $data['harga_jual'] = (int) preg_replace('/[^\d]/', '', $data['harga_jual']);
        $data['komisi_sales'] = $request->filled('komisi_sales')
            ? (int) preg_replace('/[^\d]/', '', $data['komisi_sales'])
            : null;

        // Validasi harga minimum
        [$isValid, $hargaMinimal] = $this->validateMinimumPrice($data['product_id'], $data['harga_jual'], $data['komisi_sales']);
        if (!$isValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'harga_jual' => 'Harga minimal untuk produk ini adalah Rp ' . number_format($hargaMinimal, 0, ',', '.') . ' (harga supplier tertinggi + margin Rp 5.000)'
                ]);
        }

        $price->update($data);

        return redirect()->route('customer-prices.index')->with('success', 'Harga customer berhasil diperbarui.');
    }

    private function validateMinimumPrice($productId, $hargaJual, $komisiSales)
    {
        $product = Product::with('productPrices')->findOrFail($productId);
        $maxSupplierPrice = $product->productPrices->max('harga') ?? 0;
        $minHarga = $maxSupplierPrice + 5000;
        $nett = $hargaJual - ($komisiSales ?? 0);

        return [$nett >= $minHarga, $minHarga];
    }
}
