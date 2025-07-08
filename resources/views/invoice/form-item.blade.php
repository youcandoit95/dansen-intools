<form action="{{ route('invoice-item.store') }}" method="POST" class="mb-6" id="invoiceItemForm">
    @csrf
    <input type="hidden" name="inv_id" value="{{ $invoice->id }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kiri: Form --}}
        <div class="space-y-3">
            {{-- Produk --}}
            <div>
                <label class="block mb-1">Produk</label>
                <select name="product_id" id="productSelect"
                    class="tomselect w-full border rounded px-3 py-2 @error('product_id') border-red-500 @enderror">
                    <option value="">Pilih Produk</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->nama }}</option>
                    @endforeach
                </select>
                @error('product_id')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Jika produk tidak muncul, silakan hubungi Admin.</p>
            </div>

            {{-- Stok ID --}}
            <div>
                <label class="block mb-1">Stok ID</label>
                <select name="stok_id" id="stokSelect"
                    class="tomselect w-full border rounded px-3 py-2 @error('stok_id') border-red-500 @enderror">
                    <option value="">Pilih Stok</option>
                </select>
                @error('stok_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Harga Jual --}}
            <div>
                <label class="block mb-1">Harga Jual</label>
                <input type="number" name="sell_price" id="sellPriceInput" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100 @error('sell_price') border-red-500 @enderror">
                @error('sell_price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Qty --}}
            <div>
                <label class="block mb-1">Qty</label>
                <input type="number" name="qty" id="qtyInput" readonly min="1"
                    class="w-full border rounded px-3 py-2 bg-gray-100 @error('qty') border-red-500 @enderror">
                @error('qty') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-sm text-gray-700 mt-2">Total Harga: <span id="totalHargaText" class="font-semibold">Rp 0</span></p>
            </div>

            <div class="text-right pt-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Produk</button>
            </div>
        </div>

        {{-- Kanan: Detail Produk --}}
        <div id="productDetails" class="bg-gray-50 border rounded p-4 text-sm hidden">
            <h2 class="font-semibold mb-3 text-base">Informasi Produk</h2>
            <div class="grid grid-cols-1 gap-2">
                <p><strong>Nama:</strong> <span id="namaProduk"></span></p>
                <p><strong>Kategori:</strong> <span id="kategoriProduk"></span></p>
                <p><strong>Brand:</strong> <span id="brandProduk"></span></p>
                <p><strong>Marbling Score:</strong> <span id="mbsProduk"></span></p>
                <p><strong>Bagian Daging:</strong> <span id="bagianProduk"></span></p>
                <p><strong>Deskripsi:</strong> <span id="deskripsiProduk"></span></p>
                <div><strong>Foto:</strong>
                    <div id="fotoProduk" class="mt-2 flex flex-wrap gap-2"></div>
                </div>
            </div>

            <hr class="my-4 border-gray-300">

            <h3 class="font-semibold mb-2">Harga Berdasarkan Persentase</h3>
            <table class="w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-2 py-1">Tipe</th>
                        <th class="text-right px-2 py-1">Harga</th>
                    </tr>
                </thead>
                <tbody id="priceTableBody"></tbody>
            </table>

            <div class="mt-4">
                <strong>Harga Jual Customer:</strong>
                <p id="hargaCustomer" class="text-blue-600 font-semibold mt-1"></p>
            </div>
        </div>
    </div>
</form>

@php
    $platform = $invoice->platform_id == '' ? 'offline' : 'online';
@endphp

@section('scripts')
@parent
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productSelect = document.getElementById('productSelect');
        const stokSelect = document.getElementById('stokSelect');
        const sellPriceInput = document.getElementById('sellPriceInput');
        const qtyInput = document.getElementById('qtyInput');
        const totalHargaText = document.getElementById('totalHargaText');

        const namaProduk = document.getElementById('namaProduk');
        const kategoriProduk = document.getElementById('kategoriProduk');
        const brandProduk = document.getElementById('brandProduk');
        const mbsProduk = document.getElementById('mbsProduk');
        const bagianProduk = document.getElementById('bagianProduk');
        const deskripsiProduk = document.getElementById('deskripsiProduk');
        const fotoProduk = document.getElementById('fotoProduk');
        const hargaCustomer = document.getElementById('hargaCustomer');
        const productDetails = document.getElementById('productDetails');
        const priceTableBody = document.getElementById('priceTableBody');

        function updateTotalHarga() {
            const qty = parseFloat(qtyInput.value) || 0;
            const harga = parseFloat(sellPriceInput.value) || 0;
            const total = qty * harga;
            totalHargaText.innerText = `Rp ${new Intl.NumberFormat('id-ID').format(Math.ceil(total))}`;

        }

        productSelect.addEventListener('change', async function () {
            const productId = this.value;
            if (!productId) return;

            // STOK
            const stokRes = await fetch(`/api/stok-by-product/${productId}`);
            const stokData = await stokRes.json();
            if (stokSelect.tomselect) stokSelect.tomselect.destroy();
            const stokTomSelect = new TomSelect(stokSelect, { create: false, sortField: 'text' });
            stokTomSelect.clearOptions();
            stokData.forEach(stok => {
                stokTomSelect.addOption({
                    value: stok.id,
                    text: `${stok.berat_kg} kg - ${stok.kategori} - ${stok.barcode_stok}`,
                    data: stok // penting: simpan data berat di data-* atribut
                });
            });
            stokTomSelect.refreshOptions();

            // HARGA CUSTOMER
            const hargaRes = await fetch(`/api/customer-price-by-product/{{ $invoice->customer_id }}/${productId}?platform={{ $platform }}`);
            const hargaData = await hargaRes.json();
            sellPriceInput.value = hargaData.harga ?? 0;
            hargaCustomer.innerText = hargaData.harga
                ? `Rp ${new Intl.NumberFormat('id-ID').format(hargaData.harga)}`
                : 'Tidak tersedia';
            updateTotalHarga();

            // DETAIL PRODUK
            const detailRes = await fetch(`/api/product-detail/${productId}`);
            const d = await detailRes.json();
            productDetails.classList.remove('hidden');
            namaProduk.innerText = d.nama;
            kategoriProduk.innerText = d.kategori;
            brandProduk.innerText = d.brand;
            mbsProduk.innerText = d.mbs;
            bagianProduk.innerText = d.bagian;
            deskripsiProduk.innerText = d.deskripsi;
            fotoProduk.innerHTML = '';
            d.images.forEach(img => {
                fotoProduk.innerHTML += `<img src="${img}" class="w-20 h-20 object-cover rounded" />`;
            });

            // Harga Persent
            priceTableBody.innerHTML = '';
            Object.entries(d.hargaPersent).forEach(([tipe, harga]) => {
                priceTableBody.innerHTML += `
                    <tr><td class="px-2 py-1 capitalize">${tipe}</td>
                    <td class="px-2 py-1 text-right">Rp ${new Intl.NumberFormat('id-ID').format(harga)}</td></tr>`;
            });
        });

        // Ketika stok dipilih, isi qty berdasarkan berat stok
        document.addEventListener('change', function (e) {
            if (e.target.id === 'stokSelect') {
                const selectedOption = e.target.selectedOptions[0];
                if (!selectedOption) return;
                const label = selectedOption.textContent;
                const match = label.match(/^([\d.]+) kg/);
                if (match) {
                    const berat = parseFloat(match[1]) || 1;
                    qtyInput.value = berat;
                    updateTotalHarga();
                }
            }
        });
    });
</script>
@endsection
