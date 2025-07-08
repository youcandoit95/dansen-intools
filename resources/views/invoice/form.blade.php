<div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

    <input type="hidden" name="id" value="{{ $invoice->id }}">

    <div>
        <label class="block mb-1">Tanggal Transaksi</label>
        <input type="date" name="invoice_transaction_date" value="{{ old('invoice_transaction_date', $invoice->invoice_transaction_date ? \Carbon\Carbon::parse($invoice->invoice_transaction_date)->format('Y-m-d') : '') }}"
            class="w-full border rounded px-3 py-2 @error('invoice_transaction_date') border-red-500 @enderror" required>
        @error('invoice_transaction_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">Customer</label>
        <select name="customer_id" id="customerSelect"
            class="tomselect w-full border rounded px-3 py-2 @error('customer_id') border-red-500 @enderror" @if(isset($invoice->id)) disabled @endif required>
            <option value="">Pilih Customer</option>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}"
                data-agent-name="{{ $customer->salesAgent->nama ?? '' }}"
                data-company-name="{{ $customer->company->nama ?? '' }}"
                {{ old('customer_id', $invoice->customer_id ?? '') == $customer->id ? 'selected' : '' }}>
                {{ $customer->id }} - {{ $customer->nama }}
            </option>
            @endforeach
        </select>
        @error('customer_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">Perusahaan</label>
        <input type="text" name="company_name" id="companyName"
            class="w-full border rounded px-3 py-2 bg-gray-100"
            value="{{ $invoice->customer?->company?->nama ?? '-' }}" readonly>

        @if ($invoice->customer?->company?->blacklist)
        <span class="text-red-600 font-semibold">(sedang black list)</span>
        @endif
    </div>

    <div>
        <label class="block mb-1">Sales Agent</label>
        <input type="text" name="sales_agents_name" id="salesAgentName"
            class="w-full border rounded px-3 py-2 bg-gray-100"
            value="{{ $invoice->customer?->salesAgent?->nama ?? '-' }}" readonly>
    </div>



    <div>
        <label class="block mb-1">Platform</label>
        <select name="platform_id" class="tomselect w-full border rounded px-3 py-2" @if(isset($invoice->id)) disabled @endif>
            <option value="">Offline</option>
            <option value="1" @selected(old('platform_id', $invoice->platform_id) == '1')>Tokopedia</option>
            <option value="2" @selected(old('platform_id', $invoice->platform_id) == '2')>TiktokShop</option>
            <option value="3" @selected(old('platform_id', $invoice->platform_id) == '3')>Shopee</option>
            <option value="4" @selected(old('platform_id', $invoice->platform_id) == '4')>Blibli</option>
            <option value="5" @selected(old('platform_id', $invoice->platform_id) == '5')>Toco</option>
        </select>
    </div>
</div>

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi TomSelect hanya jika belum diinisialisasi
        document.querySelectorAll(".tomselect").forEach(el => {
            if (!el.tomselect) {
                new TomSelect(el, {
                    create: false
                });
            }
        });

        const customerSelect = document.getElementById('customerSelect');
        const salesAgentInput = document.getElementById('salesAgentName');
        const companyInput = document.getElementById('companyName');

        // Atur selectedValue secara manual jika belum ter-set
        if (customerSelect && customerSelect.tomselect) {
            const selectedValue = "{{ old('customer_id', $invoice->customer_id) }}";
            customerSelect.tomselect.setValue(selectedValue);
        }

        // // Update tampilan nama sales agent dan perusahaan saat customer dipilih
        if (customerSelect) {
            customerSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const agentName = selectedOption.getAttribute('data-agent-name') || '';
                const companyName = selectedOption.getAttribute('data-company-name') || '';

                salesAgentInput.value = agentName;
                companyInput.value = companyName;
            });
        }
    });
</script>
@endsection
