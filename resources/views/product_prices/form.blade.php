@php $isEdit = isset($productPrice); @endphp

<x-alert-error />

{{-- Produk --}}
<div class="mb-4">
    <label for="product_id" class="block text-sm font-medium text-gray-700">Produk <span class="text-red-500">*</span></label>
    <select id="product_id" name="product_id" class="tom-select w-full border rounded px-3 py-2" required>
        <option value="">-- Pilih Produk --</option>
        @foreach($products as $p)
        <option value="{{ $p->id }}"
            data-info="{{ json_encode([
                    'nama' => $p->nama,
                    'barcode' => $p->barcode,
                    'kategori' => $p->kategori_label,
                    'brand' => $p->brand_label,
                    'deskripsi' => $p->deskripsi,
                    'status' => $p->status ? 'Aktif' : 'Nonaktif'
                ]) }}"
            @selected(old('product_id', $productPrice->product_id ?? '') == $p->id)>
            {{ $p->nama }}
        </option>
        @endforeach
    </select>

    <div id="productDetail" class="mt-2 text-sm text-gray-600 leading-relaxed"></div>
</div>


{{-- Supplier --}}
<div class="mb-4">
    <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier <span class="text-red-500">*</span></label>
    <select id="supplier_id" name="supplier_id" class="tom-select w-full border rounded px-3 py-2" required>
        <option value="">-- Pilih Supplier --</option>
        @foreach($suppliers as $s)
        <option value="{{ $s->id }}" @selected(old('supplier_id', $productPrice->supplier_id ?? '') == $s->id)>
            {{ $s->name }}
        </option>
        @endforeach
    </select>
</div>

{{-- Harga --}}
<div class="mb-4">
    <label for="harga" class="block text-sm font-medium text-gray-700">Harga <span class="text-red-500">*</span></label>
    <input type="text" id="harga" name="harga"
        value="{{ old('harga', $productPrice->harga ?? '') }}"
        class="rupiah-input w-full border rounded px-3 py-2 focus:outline-none focus:ring"
        required>
</div>


<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#product_id');
        new TomSelect('#supplier_id');

        const hargaInput = document.querySelector('#harga');
        hargaInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            this.value = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value || 0);
        });

        const productSelect = document.querySelector('#product_id');
        const productDetail = document.querySelector('#productDetail');

         // Tambahkan fungsi formatRupiah
    function formatRupiah(angka, prefix = 'Rp ') {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return prefix + rupiah + (split[1] ? ',' + split[1] : '');
    }

    // Format saat pertama kali dimuat (edit mode)
    if (hargaInput && hargaInput.value) {
        hargaInput.value = formatRupiah(hargaInput.value);
    }

        productSelect.addEventListener('change', function() {
            const selected = productSelect.selectedOptions[0];
            const info = selected.dataset.info ? JSON.parse(selected.dataset.info) : null;

            if (info) {
                productDetail.innerHTML = `
    <div class="overflow-x-auto mt-2">
        <table class="min-w-full text-sm text-left text-gray-700 border border-gray-300">
            <tbody>
                <tr class="border-t">
                    <th class="px-3 py-2 bg-gray-50 w-32">Nama</th>
                    <td class="px-3 py-2">${info.nama}</td>
                </tr>
                <tr class="border-t">
                    <th class="px-3 py-2 bg-gray-50">Barcode</th>
                    <td class="px-3 py-2">${info.barcode}</td>
                </tr>
                <tr class="border-t">
                    <th class="px-3 py-2 bg-gray-50">Kategori</th>
                    <td class="px-3 py-2">${info.kategori}</td>
                </tr>
                <tr class="border-t">
                    <th class="px-3 py-2 bg-gray-50">Brand</th>
                    <td class="px-3 py-2">${info.brand}</td>
                </tr>
                <tr class="border-t">
                    <th class="px-3 py-2 bg-gray-50">Deskripsi</th>
                    <td class="px-3 py-2">${info.deskripsi}</td>
                </tr>
                <tr class="border-t">
                    <th class="px-3 py-2 bg-gray-50">Status</th>
                    <td class="px-3 py-2 font-semibold ${info.status === 'Aktif' ? 'text-green-600' : 'text-red-600'}">${info.status}</td>
                </tr>
            </tbody>
        </table>
    </div>
`;

            } else {
                productDetail.innerHTML = '';
            }
        });

        if (productSelect.value) {
            productSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
