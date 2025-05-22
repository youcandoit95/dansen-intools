@php
    $isEdit = isset($defaultSellPrice);
    $channels = [
        "online_sell_price" => "Online",
        "offline_sell_price" => "Offline",
        "reseller_sell_price" => "Reseller",
        "resto_sell_price" => "Resto",
        "bottom_sell_price" => "Harga Terendah",
    ];
@endphp

{{-- Pilih Produk --}}
<div class="mb-4">
    <label for="product_id" class="block text-sm font-medium text-gray-700">
        Produk <span class="text-red-500">*</span>
    </label>
    <select id="product_id" name="product_id" class="tom-select w-full border rounded px-3 py-2" required>
        <option value="">-- Pilih Produk --</option>
        @foreach($products as $p)
        <option value="{{ $p->id }}"
            data-info="{{ json_encode([
                "nama" => $p->nama,
                "barcode" => $p->barcode,
                "kategori" => $p->kategori_label,
                "brand" => $p->brand_label,
                "deskripsi" => $p->deskripsi,
                "status" => $p->status ? "Aktif" : "Nonaktif",
                "mbs" => optional($p->mbs)->bms ? optional($p->mbs)->bms . " (" . optional($p->mbs)->a_grade . ")" : "-",
                "bagian" => optional($p->bagianDaging)->nama ?? "-"
            ]) }}"
            data-prices="{{ json_encode(
                $p->productPrices->map(fn($pp) => [
                    "supplier" => ["name" => $pp->supplier->nama ?? "-"],
                    "harga" => $pp->harga
                ])
            ) }}"
            @selected(old("product_id", $defaultSellPrice->product_id ?? "") == $p->id)>
            {{ $p->nama }}
        </option>
        @endforeach
    </select>
</div>

{{-- Detail Produk --}}
<div id="productDetail" class="mt-4 text-sm text-gray-700 leading-relaxed"></div>

{{-- Daftar Harga dari Supplier --}}
<div id="priceTable" class="mt-4 hidden"></div>

{{-- Input Harga Default per Channel --}}
<div class="mt-6 border-t pt-4">
    <h3 class="font-semibold mb-3 text-gray-700">Harga Default per Channel</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($channels as $field => $label)
        <div>
            <label for="{{ $field }}" class="block text-sm font-medium text-gray-700">
                {{ $label }} <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="{{ $field }}"
                   name="{{ $field }}"
                   value="{{ old($field, $defaultSellPrice->{$field} ?? '') }}"
                   class="rupiah-input w-full border rounded px-3 py-2 focus:outline-none focus:ring"
                   required>
        </div>
        @endforeach
    </div>
</div>

{{-- Tombol --}}
<div class="mt-6">
    <button type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        {{ $isEdit ? "Update" : "Simpan" }}
    </button>
</div>

{{-- TomSelect & Script --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    new TomSelect("#product_id");

    const rupiah = (angka) => new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
    }).format(angka || 0);

    document.querySelectorAll(".rupiah-input").forEach(input => {
        input.addEventListener("input", function () {
            const angka = this.value.replace(/\D/g, "");
            this.value = rupiah(angka);
        });

        if (input.value && !input.value.startsWith("Rp")) {
            const angka = input.value.replace(/\D/g, "");
            input.value = rupiah(angka);
        }
    });

    const productSelect = document.getElementById("product_id");
    const productDetail = document.getElementById("productDetail");
    const priceTable = document.getElementById("priceTable");

    productSelect.addEventListener("change", function () {
        const selected = this.selectedOptions[0];
        let info = selected.getAttribute("data-info");
        let prices = selected.getAttribute("data-prices");

        try {
            info = JSON.parse(info);
            prices = JSON.parse(prices);
        } catch (e) {
            info = null;
            prices = [];
        }

        if (info) {
            productDetail.innerHTML = `
                <table class="min-w-full text-sm border border-gray-300">
                    <tbody>
                        <tr><th class="bg-gray-50 px-3 py-2 w-32">Nama</th><td class="px-3 py-2">${info.nama}</td></tr>
                        <tr><th class="bg-gray-50 px-3 py-2">Barcode</th><td class="px-3 py-2">${info.barcode}</td></tr>
                        <tr><th class="bg-gray-50 px-3 py-2">Kategori</th><td class="px-3 py-2">${info.kategori}</td></tr>
                        <tr><th class="bg-gray-50 px-3 py-2">Brand</th><td class="px-3 py-2">${info.brand}</td></tr>
                        <tr><th class="bg-gray-50 px-3 py-2">Deskripsi</th><td class="px-3 py-2">${info.deskripsi}</td></tr>
                        <tr><th class="bg-gray-50 px-3 py-2">Bagian</th><td class="px-3 py-2">${info.bagian}</td></tr>
                        <tr><th class="bg-gray-50 px-3 py-2">MBS</th><td class="px-3 py-2">${info.mbs}</td></tr>
                        <tr><th class="bg-gray-50 px-3 py-2">Status</th>
                            <td class="px-3 py-2 font-semibold ${info.status === "Aktif" ? "text-green-600" : "text-red-600"}">${info.status}</td>
                        </tr>
                    </tbody>
                </table>
            `;
        } else {
            productDetail.innerHTML = "";
        }

        if (Array.isArray(prices) && prices.length > 0) {
            let html = `
                <h3 class="font-semibold text-gray-800 mb-2">Harga dari Supplier</h3>
                <table class="min-w-full text-sm border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 border">Supplier</th>
                            <th class="px-3 py-2 border">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            prices.forEach(p => {
                html += `
                    <tr>
                        <td class="px-3 py-2 border">${p.supplier.name}</td>
                        <td class="px-3 py-2 border">${rupiah(p.harga)}</td>
                    </tr>
                `;
            });
            html += `</tbody></table>`;
            priceTable.innerHTML = html;
            priceTable.classList.remove("hidden");
        } else {
            priceTable.innerHTML = "";
            priceTable.classList.add("hidden");
        }
    });

    if (productSelect.value) {
        productSelect.dispatchEvent(new Event("change"));
    }
});
</script>
