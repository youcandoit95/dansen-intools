<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\ProductPrice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $data = PurchaseOrder::with('supplier')
            ->when(!session('superadmin'), function ($query) {
                $query->where('cabang_id', session('cabang_id'));
            })
            ->latest()
            ->get();
        $activeMenu = 'purchase-order';
        return view('purchase-order.index', compact('data', 'activeMenu'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('nama')->get();
        $activeMenu = 'purchase-order';

        return view('purchase-order.create', compact('suppliers', 'products', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_po' => 'required|unique:purchase_orders',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'tanggal_permintaan_dikirim' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        $po = PurchaseOrder::create([
            'no_po' => $request->no_po,
            'supplier_id' => $request->supplier_id,
            'tanggal' => $request->tanggal,
            'tanggal_permintaan_dikirim' => $request->tanggal_permintaan_dikirim,
            'catatan' => $request->catatan,
            'cabang_id' => session('cabang_id'), // jika digunakan
            'created_by' => session('user_id'),
        ]);

        return redirect()->route('purchase-order.edit', $po->id)
            ->with('success', 'PO berhasil disimpan. Silakan tambahkan item.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $activeMenu = 'purchase-order';
        return view('purchase-order.show', compact('purchaseOrder', 'activeMenu'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::orderBy('name')->get();

        // Ambil semua product_id yang sudah digunakan dalam PO ini
        $usedProductIds = $purchaseOrder->items->pluck('product_id')->toArray();

        // Ambil daftar produk dari supplier yang sama DAN belum digunakan di PO
        $products = ProductPrice::with('product')
            ->where('supplier_id', $purchaseOrder->supplier_id)
            ->whereNotIn('product_id', $usedProductIds)
            ->orderBy('product_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'nama' => $item->product->nama,
                    'harga_beli' => $item->harga,
                ];
            });

        $purchaseOrder->load([
            'ajukanBy',
            'items.product' => function ($q) use ($purchaseOrder) {
                $q->with(['productPrices' => function ($subQuery) use ($purchaseOrder) {
                    $subQuery->where('supplier_id', $purchaseOrder->supplier_id);
                }]);
            }
        ]);

        // Hitung total qty dan subtotal berdasarkan harga productPrice dari supplier
        $totalQty = $purchaseOrder->items->sum('qty');
        $totalSubtotal = $purchaseOrder->items->sum(function ($item) use ($purchaseOrder) {
            $hargaBeli = $item->product->productPrices
                ->where('supplier_id', $purchaseOrder->supplier_id)
                ->max('harga') ?? 0;

            return $item->qty * $hargaBeli;
        });

        $activeMenu = 'purchase-order';

        return view('purchase-order.edit', compact(
            'purchaseOrder',
            'suppliers',
            'products',
            'activeMenu',
            'totalQty',
            'totalSubtotal'
        ));
    }


    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'tanggal_permintaan_dikirim' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        // Cek apakah supplier berubah
        $supplierChanged = $request->supplier_id != $purchaseOrder->supplier_id;

        // Update PO
        $purchaseOrder->update([
            'supplier_id' => $request->supplier_id,
            'tanggal' => $request->tanggal,
            'tanggal_permintaan_dikirim' => $request->tanggal_permintaan_dikirim,
            'catatan' => $request->catatan,
            'updated_by' => session('user_id'),
        ]);

        // Hard delete semua item jika supplier berubah
        if ($supplierChanged) {
            $purchaseOrder->items()->forceDelete();
        }

        return redirect()->route('purchase-order.edit', $purchaseOrder->id)
            ->with('success', 'PO berhasil diperbarui' . ($supplierChanged ? ' dan semua item dihapus karena supplier berubah.' : '.'));
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->sendemail_at) {
            return redirect()->back()->with('error', 'PO sudah dikirim email dan tidak bisa dibatalkan.');
        }

        $purchaseOrder->update(['deleted_by' => session('user_id')]);
        $purchaseOrder->delete();

        return redirect()->route('purchase-order.index')->with('success', 'Purchase Order berhasil dibatalkan dan dihapus.');
    }


    public function ajukan(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->ajukan_at) {
            return redirect()->back()->with('error', 'Purchase Order sudah diajukan sebelumnya.');
        }

        $purchaseOrder->ajukan_at = now();
        $purchaseOrder->ajukan_by = session('user_id');
        $purchaseOrder->save();

        return redirect()->route('purchase-order.edit', $purchaseOrder->id)
            ->with('success', 'Purchase Order berhasil diajukan.');
    }

    public function kirimEmail(PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->ajukan_at) {
            return redirect()->back()->with('error', 'PO belum diajukan.');
        }

        if ($purchaseOrder->sendemail_at) {
            return redirect()->back()->with('error', 'PO sudah pernah dikirim email.');
        }

        // Simulasi kirim email atau integrasi di sini
        // Mail::to(...)->send(...);

        // Update waktu dan user pengirim
        $purchaseOrder->sendemail_at = now();
        $purchaseOrder->sendemail_by = session('user_id');
        $purchaseOrder->save();

        return redirect()->route('purchase-order.edit', $purchaseOrder->id)
            ->with('success', 'Email PO berhasil dikirim.');
    }

    public function getPoDetail($id)
{
    $po = PurchaseOrder::with(['supplier', 'items.product'])->findOrFail($id);

    return response()->json([
        'no_po' => $po->no_po,
        'supplier' => $po->supplier->name ?? '-',
        'supplier_id' => $po->supplier_id,
        'tanggal' => $po->tanggal,
        'catatan' => $po->catatan,
        'items' => $po->items->map(function ($item) use ($po) {
            return [
                'produk' => $item->product->nama ?? '-',
                'qty' => $item->qty,
                'harga' => optional($item->product->productPrices->where('supplier_id', $po->supplier_id)->first())->harga ?? 0,
                'berat' => $item->total_berat ?? '',
            ];
        }),
    ]);
}



}
