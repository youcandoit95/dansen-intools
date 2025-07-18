<form action="{{ route('invoice-item.store') }}" method="POST" class="mb-6" id="invoiceItemForm">
    @csrf
    <input type="hidden" name="inv_id" value="{{ $invoice->id }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kiri: Form --}}
        <div class="space-y-3">
            {{-- Produk --}}
            <div>
                <label class="block mb-1 text-sm">Produk</label>
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
                <label class="block mb-1 text-sm">Stok ID</label>
                <select name="stok_id" id="stokSelect"
                    class="tomselect w-full border rounded px-3 py-2 @error('stok_id') border-red-500 @enderror">
                    <option value="">Pilih Stok</option>
                </select>
                @error('stok_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Harga Jual --}}
            <div>
                <label class="block mb-1 text-sm">Harga Jual</label>
                <input type="number" name="sell_price" id="sellPriceInput" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100 @error('sell_price') border-red-500 @enderror">
                @error('sell_price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Qty Inbound (readonly dari stok) --}}
            <div>
                <label class="block mb-1 text-sm">Qty Inbound (Stok Masuk)</label>
                <input type="number" name="qty" id="qtyInput" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100 @error('qty') border-red-500 @enderror">
                <p class="text-sm text-gray-700 mt-2">Total Harga Berat Inbound: <span id="totalHargaText" class="font-semibold">Rp 0</span></p>
            </div>

            {{-- Qty Outbound --}}
            <div>
                <label class="block mb-1 text-sm">Qty Outbound (Stok Keluar)</label>
                <input type="number" name="qty_out" id="qtyOutInput" step="0.001" min="0.001"
                    class="w-full border rounded px-3 py-2 @error('qty_out') border-red-500 @enderror">
                @error('qty_out') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                <div class="mt-2 text-right">
                    <button type="button" id="copyQtyInbound"
                        class="text-sm text-blue-600 hover:underline">
                        Klik jika berat sama dengan inbound
                    </button>
                </div>
            </div>


            {{-- Informasi Susut dan Total Harga Out --}}
            <div class="space-y-1 text-sm text-gray-700">
                <p>Susut (Waste): <span id="wasteText" class="font-semibold text-gray-800">0.000 kg</span></p>
                <p>Total Harga Jual Outbound: <span id="totalHargaOutText" class="font-semibold text-blue-600">Rp 0</span></p>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block mb-1 text-sm">Catatan</label>
                <textarea name="note" rows="2"
                    class="w-full border rounded px-3 py-2 @error('note') border-red-500 @enderror">{{ old('note') }}</textarea>
                @error('note') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
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
    document.getElementById('copyQtyInbound').addEventListener('click', function() {
        const qtyInbound = parseFloat(qtyInput.value) || 0;
        qtyOutInput.value = qtyInbound.toFixed(3);
        updateWasteAndHargaOut();
    });


    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('productSelect');
        const stokSelect = document.getElementById('stokSelect');
        const sellPriceInput = document.getElementById('sellPriceInput');
        const qtyInput = document.getElementById('qtyInput');
        const qtyOutInput = document.getElementById('qtyOutInput');
        const totalHargaText = document.getElementById('totalHargaText');
        const totalHargaOutText = document.getElementById('totalHargaOutText');
        const wasteText = document.getElementById('wasteText');

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

        function updateWasteAndHargaOut() {
            const qtyIn = parseFloat(qtyInput.value) || 0;
            const qtyOut = parseFloat(qtyOutInput.value) || 0;
            const harga = parseFloat(sellPriceInput.value) || 0;

            let waste = qtyIn - qtyOut;
            if (waste < 0) {
                waste = 0;
                wasteText.classList.add('text-red-600');
            } else {
                wasteText.classList.remove('text-red-600');
            }

            const totalOut = qtyOut * harga;
            wasteText.innerText = `${waste.toFixed(3)} kg`;
            totalHargaOutText.innerText = `Rp ${new Intl.NumberFormat('id-ID').format(Math.ceil(totalOut))}`;
        }

        productSelect.addEventListener('change', async function() {
            const productId = this.value;
            if (!productId) return;

            // Fetch stok list berdasarkan produk
            const stokRes = await fetch(`/api/stok-by-product/${productId}`);
            const stokData = await stokRes.json();

            if (stokSelect.tomselect) stokSelect.tomselect.destroy();
            const stokTomSelect = new TomSelect(stokSelect, {
                create: false,
                sortField: 'text'
            });
            stokTomSelect.clearOptions();

            stokData.forEach(stok => {
                stokTomSelect.addOption({
                    value: stok.id,
                    text: `${stok.berat_kg} kg - ${stok.kategori} - ${stok.barcode_stok}`,
                    data: stok
                });
            });

            stokTomSelect.refreshOptions();
        });

        stokSelect.addEventListener('change', async function() {
            const stokId = this.value;
            if (!stokId) return;

            // Ambil detail stok
            const detailRes = await fetch(`/api/stock-detail/${stokId}`);
            const d = await detailRes.json();

            productDetails.classList.remove('hidden');
            namaProduk.innerText = d.produk.nama;
            kategoriProduk.innerText = d.kategori;
            brandProduk.innerText = d.produk.brand;
            mbsProduk.innerText = d.produk.mbs;
            bagianProduk.innerText = d.produk.bagian;
            deskripsiProduk.innerText = d.produk.deskripsi;

            fotoProduk.innerHTML = '';

            if (d.produk && Array.isArray(d.produk.images)) {
                d.produk.images.forEach(img => {
                    fotoProduk.innerHTML += `<img src="${img}" class="w-20 h-20 object-cover rounded" />`;
                });
            } else {
                fotoProduk.innerHTML = `<p class="text-gray-500 italic">Tidak ada foto produk</p>`;
            }


            // Tampilkan harga persentase
            priceTableBody.innerHTML = '';

            if (d.produk && d.produk.hargaPersent) {
                Object.entries(d.produk.hargaPersent).forEach(([tipe, harga]) => {
                    priceTableBody.innerHTML += `
            <tr>
                <td class="px-2 py-1 capitalize">${tipe}</td>
                <td class="px-2 py-1 text-right">Rp ${new Intl.NumberFormat('id-ID').format(harga)}</td>
            </tr>`;
                });
            }


            // Harga customer
            const hargaRes = await fetch(`/api/customer-price-by-product/{{ $invoice->customer_id }}/${d.produk_id}?platform={{ $platform }}`);
            const hargaData = await hargaRes.json();
            sellPriceInput.value = hargaData.harga ?? 0;
            hargaCustomer.innerText = hargaData.harga ?
                `Rp ${new Intl.NumberFormat('id-ID').format(hargaData.harga)}` :
                'Tidak tersedia';

            updateTotalHarga();
            updateWasteAndHargaOut();
        });

        // Ambil qty dari label stok
        document.addEventListener('change', function(e) {
            if (e.target.id === 'stokSelect') {
                const selectedOption = e.target.selectedOptions[0];
                if (!selectedOption) return;
                const label = selectedOption.textContent;
                const match = label.match(/^([\d.]+) kg/);
                if (match) {
                    const berat = parseFloat(match[1]) || 1;
                    qtyInput.value = berat;
                    updateTotalHarga();
                    updateWasteAndHargaOut();
                }
            }
        });

        qtyOutInput.addEventListener('input', function() {
            updateWasteAndHargaOut();
        });
    });
</script>
@endsection
