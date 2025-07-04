<div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
    <div>
        <label class="block mb-1">Nomor Invoice</label>
        <input type="text" name="inv_no" value="{{ old('inv_no', $invoice->inv_no) }}"
            class="w-full border rounded px-3 py-2 @error('inv_no') border-red-500 @enderror">
        @error('inv_no') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">Tanggal Transaksi</label>
        <input type="date" name="invoice_transaction_date" value="{{ old('invoice_transaction_date', $invoice->invoice_transaction_date ? \Carbon\Carbon::parse($invoice->invoice_transaction_date)->format('Y-m-d') : '') }}"
            class="w-full border rounded px-3 py-2 @error('invoice_transaction_date') border-red-500 @enderror">
        @error('invoice_transaction_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">Perusahaan</label>
        <select name="company_id" class="tomselect w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
            <option value="">Pilih Perusahaan</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" @selected(old('company_id', $invoice->company_id) == $company->id)>
                    {{ $company->nama }}
                </option>
            @endforeach
        </select>
        @error('company_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">Customer</label>
        <select name="customer_id" class="tomselect w-full border rounded px-3 py-2 @error('customer_id') border-red-500 @enderror">
            <option value="">Pilih Customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" @selected(old('customer_id', $invoice->customer_id) == $customer->id)>
                    {{ $customer->nama }}
                </option>
            @endforeach
        </select>
        @error('customer_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">Sales Agent</label>
        <select name="sales_agents_id" class="tomselect w-full border rounded px-3 py-2">
            <option value="">Pilih Sales Agent</option>
            @foreach($salesAgents as $agent)
                <option value="{{ $agent->id }}" @selected(old('sales_agents_id', $invoice->sales_agents_id) == $agent->id)>
                    {{ $agent->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1">Total Invoice (Rp)</label>
        <input type="number" name="g_total_invoice_amount" value="{{ old('g_total_invoice_amount', $invoice->g_total_invoice_amount) }}"
            class="w-full border rounded px-3 py-2 @error('g_total_invoice_amount') border-red-500 @enderror">
        @error('g_total_invoice_amount') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>
