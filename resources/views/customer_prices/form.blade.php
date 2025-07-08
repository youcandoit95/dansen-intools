@php
$selectedProduct = old('product_id', $price->product_id ?? '');
$hargaJual = old('harga_jual', $price->harga_jual ?? '');
$komisiSales = old('komisi_sales', $price->komisi_sales ?? '');
$customerSelected = old('customer_id', $price->customer_id ?? '');
@endphp

<form action="{{ isset($price) ? route('customer-prices.update', $price->id) : route('customer-prices.store') }}"
    method="POST"
    class="bg-white p-6 rounded shadow max-w-2xl space-y-5">

    @csrf
    @if(isset($price)) @method('PUT') @endif

    @if(isset($price->id))
    <input type="hidden" name="id" value="{{ $price->id }}">
    @endif


    <!-- Customer -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>
        <select name="customer_id"
            id="customerSelect"
            class="tom-select w-full border rounded px-3 py-2"
            required>
            <option value="">-- Pilih Customer --</option>
            @foreach($customers as $cust)
            <option value="{{ $cust->id }}"
                data-sales-agent="{{ $cust->salesAgent->nama ?? '' }}"
                {{ $customerSelected == $cust->id ? 'selected' : '' }}>
                {{ $cust->nama }}
            </option>
            @endforeach
        </select>
        <div id="salesAgentInfo" class="mt-2 text-sm text-gray-600 hidden">
            <strong>Sales Agent:</strong> <span id="salesAgentName">-</span>
        </div>
    </div>

    <!-- Produk -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk <span class="text-red-500">*</span></label>
        <select name="product_id" id="productSelect" class="tom-select w-full border rounded px-3 py-2" required>
            <option value="">-- Pilih Produk --</option>
            @foreach($products as $p)
            <option value="{{ $p->id }}"
                {{ $selectedProduct == $p->id ? 'selected' : '' }}
                data-info="{{ json_encode([
                        'nama' => $p->nama,
                        'max_supplier_price' => $p->productPrices->max('harga'),
                        'default_prices' => [
                            'online' => $p->defaultSellPrice->online_sell_price ?? 0,
                            'offline' => $p->defaultSellPrice->offline_sell_price ?? 0,
                            'reseller' => $p->defaultSellPrice->reseller_sell_price ?? 0,
                            'resto' => $p->defaultSellPrice->resto_sell_price ?? 0,
                            'bottom' => $p->defaultSellPrice->bottom_sell_price ?? 0
                        ]
                    ]) }}">
                {{ $p->nama }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Info Harga -->
    <div id="hargaInfo" class="hidden border rounded p-4 bg-gray-50 text-sm"></div>

    <!-- Harga Jual -->
    <div>
        @error('harga_jual')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
        <input type="text" name="harga_jual" class="rupiah-input w-full border rounded px-3 py-2"
            value="{{ 'Rp ' . number_format((int)preg_replace('/[^0-9]/', '', $hargaJual), 0, ',', '.') }}">
    </div>

    <!-- Komisi Sales -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Komisi Sales Agent (Rp)</label>
        <input type="text" name="komisi_sales" id="komisi_sales" class="rupiah-input w-full border rounded px-3 py-2"
            value="{{ 'Rp ' . number_format((int)preg_replace('/[^0-9]/', '', $komisiSales), 0, ',', '.') }}">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
</form>

<!-- Include TomSelect -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#productSelect');
        new TomSelect('#customerSelect');

        const customerSelect = document.getElementById('customerSelect');
        const salesAgentInfo = document.getElementById('salesAgentInfo');
        const salesAgentName = document.getElementById('salesAgentName');
        const komisiInput = document.getElementById('komisi_sales');

        // Tampilkan sales agent saat pilih customer
        customerSelect?.addEventListener('change', function() {
            const selected = this.selectedOptions[0];
            const agentName = selected.getAttribute('data-sales-agent');
            if (agentName) {
                salesAgentName.textContent = agentName;
                salesAgentInfo.classList.remove('hidden');
                komisiInput.disabled = false;
            } else {
                salesAgentName.textContent = '-';
                salesAgentInfo.classList.add('hidden');
                komisiInput.value = '';
                komisiInput.disabled = true;
            }
        });

        // Trigger saat halaman dimuat
        const initialCustomer = customerSelect?.selectedOptions[0];
        if (initialCustomer) {
            const agentName = initialCustomer.getAttribute('data-sales-agent');
            if (agentName) {
                salesAgentName.textContent = agentName;
                salesAgentInfo.classList.remove('hidden');
            }
        }

        // Produk info harga
        const productSelect = document.getElementById('productSelect');
        const hargaInfo = document.getElementById('hargaInfo');

        productSelect?.addEventListener('change', function() {
            const selected = this.selectedOptions[0];
            if (!selected) return hargaInfo.classList.add('hidden');

            const info = JSON.parse(selected.getAttribute('data-info'));
            const d = info.default_prices;

            hargaInfo.innerHTML = `
            <table class="w-full text-sm border border-gray-300">
                <thead class="bg-gray-100">
                    <tr><th class="px-3 py-2 text-left">Channel</th><th class="px-3 py-2 text-left">Harga</th></tr>
                </thead>
                <tbody>
                    <tr><td class="px-3 py-2">Harga Tertinggi Supplier</td><td class="px-3 py-2">Rp ${Number(info.max_supplier_price).toLocaleString()}</td></tr>
                    <tr><td class="px-3 py-2">Online</td><td class="px-3 py-2">Rp ${Number(d.online).toLocaleString()}</td></tr>
                    <tr><td class="px-3 py-2">Offline</td><td class="px-3 py-2">Rp ${Number(d.offline).toLocaleString()}</td></tr>
                    <tr><td class="px-3 py-2">Reseller</td><td class="px-3 py-2">Rp ${Number(d.reseller).toLocaleString()}</td></tr>
                    <tr><td class="px-3 py-2">Resto</td><td class="px-3 py-2">Rp ${Number(d.resto).toLocaleString()}</td></tr>
                    <tr><td class="px-3 py-2">Bottom</td><td class="px-3 py-2">Rp ${Number(d.bottom).toLocaleString()}</td></tr>
                </tbody>
            </table>`;
            hargaInfo.classList.remove('hidden');
        });

        // Format input Rp
        document.querySelectorAll('.rupiah-input').forEach(input => {
            input.addEventListener('input', function() {
                const angka = this.value.replace(/\D/g, '');
                this.value = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka || 0);
            });
        });
    });
</script>
