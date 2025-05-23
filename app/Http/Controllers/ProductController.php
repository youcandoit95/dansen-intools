<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $activeMenu = 'products';
        $products = Product::with('images')->latest()->get();
        return view('products.index', compact('products', 'activeMenu'));
    }

    public function create()
    {
        $mbsList = \App\Models\Mbs::orderBy('bms')->get();
        $bagianDagingList = \App\Models\BagianDaging::orderBy('nama')->get();

        $activeMenu = 'products';
        return view('products.create', compact('mbsList', 'bagianDagingList', 'activeMenu'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode'   => 'required|unique:products,barcode',
            'kategori'  => 'required|in:1,2,3',
            'mbs_id' => 'nullable|exists:mbs,id',
            'bagian_daging_id' => 'nullable|exists:bagian_daging,id',
            'brand'     => 'required|in:1,2,3',
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status'    => 'boolean',
            'images'    => 'nullable|array|max:5',
            'images.*'  => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
        ], [
            'barcode.required'   => 'Barcode wajib diisi.',
            'barcode.unique'     => 'Barcode sudah digunakan.',
            'kategori.required'  => 'Kategori wajib dipilih.',
            'kategori.in'        => 'Kategori tidak valid.',
            'brand.required'     => 'Brand wajib dipilih.',
            'brand.in'           => 'Brand tidak valid.',
            'nama.required'      => 'Nama produk wajib diisi.',
            'nama.max'           => 'Nama produk maksimal 100 karakter.',
            'status.boolean'     => 'Status harus berupa pilihan valid.',
            'images.array'       => 'Format upload gambar tidak valid.',
            'images.max'         => 'Maksimal 5 gambar boleh diupload.',
            'images.*.image'     => 'Setiap file harus berupa gambar.',
            'images.*.mimes'     => 'Format gambar harus jpeg, png, atau jpg.',
            'images.*.max'       => 'Ukuran gambar tidak boleh lebih dari 8MB.',
        ]);

        // ✅ panggil validasi tambahan
        $this->validateUniqueProduct($request);

        $product = Product::create($request->only([
            'barcode',
            'kategori',
            'mbs_id',
            'bagian_daging_id',
            'brand',
            'nama',
            'deskripsi',
            'status'
        ]));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = $img->store('uploads/products', 'public');
                $product->images()->create(['path' => $filename]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $mbsList = \App\Models\Mbs::orderBy('bms')->get();
        $bagianDagingList = \App\Models\BagianDaging::orderBy('nama')->get();
        $activeMenu = 'products';
        return view('products.edit', compact('product', 'mbsList', 'bagianDagingList', 'activeMenu'));
    }

    public function update(Request $request, Product $product)
    {

        $request->validate([
            'barcode' => [
                'required',
                Rule::unique('products', 'barcode')->ignore($product->id),
            ],
            'kategori'  => 'required|in:1,2,3',
            'brand'     => 'required|in:1,2,3',
            'mbs_id' => 'nullable|exists:mbs,id',
            'bagian_daging_id' => 'nullable|exists:bagian_daging,id',
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status'    => 'boolean',
            'images'    => 'nullable|array|max:5',
            'images.*'  => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
        ], [
            'barcode.required'   => 'Barcode wajib diisi.',
            'barcode.unique'     => 'Barcode sudah digunakan.',
            'kategori.required'  => 'Kategori wajib dipilih.',
            'kategori.in'        => 'Kategori tidak valid.',
            'brand.required'     => 'Brand wajib dipilih.',
            'brand.in'           => 'Brand tidak valid.',
            'nama.required'      => 'Nama produk wajib diisi.',
            'nama.max'           => 'Nama produk maksimal 100 karakter.',
            'status.boolean'     => 'Status harus berupa pilihan valid.',
            'images.array'       => 'Format upload gambar tidak valid.',
            'images.max'         => 'Maksimal 5 gambar boleh diupload.',
            'images.*.image'     => 'Setiap file harus berupa gambar.',
            'images.*.mimes'     => 'Format gambar harus jpeg, png, atau jpg.',
            'images.*.max'       => 'Ukuran gambar tidak boleh lebih dari 8MB.',
        ]);

        // ✅ panggil validasi tambahan
        $this->validateUniqueProduct($request, $product->id); // pengecualian saat update

        $product->update($request->only([
            'barcode',
            'kategori',
            'mbs_id',
            'bagian_daging_id',
            'brand',
            'nama',
            'deskripsi',
            'status'
        ]));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = $img->store('uploads/products', 'public');
                $product->images()->create(['path' => $filename]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);

        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();
        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = !$product->status;
        $product->save();

        return back()->with('success', 'Status produk diperbarui.');
    }

    public function show()
    {
        $products = Product::select('barcode', 'nama', 'kategori', 'brand', 'status', 'deskripsi')->get();

        return Excel::download(new ProductExport, 'produk.xlsx');
    }

    private function validateUniqueProduct(Request $request, $productId = null)
    {
        // Validasi nama unik (belum soft deleted)
        $queryNama = Product::where('nama', $request->nama)->whereNull('deleted_at');
        if ($productId) {
            $queryNama->where('id', '!=', $productId);
        }

        if ($queryNama->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'nama' => 'Nama produk sudah digunakan.',
            ]);
        }

        // Validasi kombinasi unik brand + mbs_id + bagian_daging_id
        $queryKombinasi = Product::where('brand', $request->brand)
            ->where('mbs_id', $request->mbs_id)
            ->where('bagian_daging_id', $request->bagian_daging_id)
            ->whereNull('deleted_at');

        if ($productId) {
            $queryKombinasi->where('id', '!=', $productId);
        }

        if ($queryKombinasi->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'brand' => 'Kombinasi brand, MBS, dan bagian daging sudah digunakan.',
            ]);
        }
    }

}
