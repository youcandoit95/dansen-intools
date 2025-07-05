<form action="{{ route('invoice-item.store') }}" method="POST" class="grid gap-3 text-sm"
      id="invoiceItemForm">
    @csrf
    <input type="hidden" name="inv_id" value="{{ $invoice->id }}">

    {{-- Produk --}}
    <div>
        <label class="block mb-1">Produk</label>
        <select name="product_id" id="productSelect" class="tomselect w-full border rounded px-3 py-2 @error('product_id') border-red-500 @enderror">
            <option value="">Pilih Produk</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->nama }}</option>
            @endforeach
        </select>
        @error('product_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Stok ID --}}
    <div>
        <label class="block mb-1">Stok ID</label>
        <select name="stok_id" id="stokSelect" class="tomselect w-full border rounded px-3 py-2 @error('stok_id') border-red-500 @enderror">
            <option value="">Pilih Stok</option>
            {{-- Akan diisi via JS --}}
        </select>
        @error('stok_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Harga Jual --}}
    <div>
        <label class="block mb-1">Harga Jual</label>
        <input type="number" name="sell_price" id="sellPriceInput"
               class="w-full border rounded px-3 py-2 @error('sell_price') border-red-500 @enderror"
               readonly>
        @error('sell_price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Qty --}}
    <div>
        <label class="block mb-1">Qty</label>
        <input type="number" name="qty" min="1"
               class="w-full border rounded px-3 py-2 @error('qty') border-red-500 @enderror"
               value="1">
        @error('qty') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="text-right">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Produk</button>
    </div>
</form>

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('productSelect');
    const stokSelect = document.getElementById('stokSelect');
    const sellPriceInput = document.getElementById('sellPriceInput');

    productSelect.addEventListener('change', async function () {
        const productId = this.value;

        if (!productId) return;

        // Fetch stok berdasarkan produk
        const stokRes = await fetch(`/api/stok-by-product/${productId}`);
        const stokData = await stokRes.json();

        stokSelect.innerHTML = '<option value="">Pilih Stok</option>';
        stokData.forEach(stok => {
            stokSelect.innerHTML += `<option value="${stok.id}">ID ${stok.id} - ${stok.berat_kg} kg</option>`;
        });

        // Fetch harga customer
        const hargaRes = await fetch(`/api/customer-price-by-product/{{ $invoice->customer_id }}/${productId}`);
        const hargaData = await hargaRes.json();

        if (hargaData && hargaData.harga) {
            sellPriceInput.value = hargaData.harga;
        } else {
            sellPriceInput.value = 0;
        }
    });
});
</script>
@endsection
