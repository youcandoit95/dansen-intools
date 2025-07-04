@extends('layouts.app')
@section('title', 'Daftar Invoice')

@section('content')
<h1 class="text-lg font-semibold mb-3">Daftar Invoice</h1>

<form method="GET" class="bg-white p-3 rounded shadow mb-4 grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
    <input type="text" name="inv_no" value="{{ request('inv_no') }}" class="w-full border rounded px-2 py-1.5" placeholder="No Invoice">

    <select name="sales_agents_id" class="tomselect w-full border rounded px-2 py-1.5">
        <option value="">Sales Agent</option>
        @foreach ($salesAgents as $agent)
            <option value="{{ $agent->id }}" @selected(request('sales_agents_id') == $agent->id)>{{ $agent->nama }}</option>
        @endforeach
    </select>

    <select name="company_id" class="tomselect w-full border rounded px-2 py-1.5">
        <option value="">Perusahaan</option>
        @foreach ($companies as $company)
            <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>{{ $company->nama }}</option>
        @endforeach
    </select>

    <select name="customer_id" class="tomselect w-full border rounded px-2 py-1.5">
        <option value="">Customer</option>
        @foreach ($customers as $customer)
            <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>{{ $customer->nama }}</option>
        @endforeach
    </select>

    <input type="number" name="min_amount" value="{{ request('min_amount') }}" class="w-full border rounded px-2 py-1.5" placeholder="Min Total">
    <input type="number" name="max_amount" value="{{ request('max_amount') }}" class="w-full border rounded px-2 py-1.5" placeholder="Max Total">

    <select name="platform_id" class="tomselect w-full border rounded px-2 py-1.5">
        <option value="">Platform</option>
        <option value="1" @selected(request('platform_id') == '1')>Tokopedia</option>
        <option value="2" @selected(request('platform_id') == '2')>TiktokShop</option>
        <option value="3" @selected(request('platform_id') == '3')>Shopee</option>
        <option value="4" @selected(request('platform_id') == '4')>Blibli</option>
        <option value="5" @selected(request('platform_id') == '5')>Toco</option>
        <option value="0" @selected(request('platform_id') === '0')>Offline</option>
    </select>

    {{-- Toggle Switches --}}
    <label class="flex items-center gap-2">
        <input type="checkbox" name="lunas" value="1" @checked(request('lunas') == '1') class="toggle-checkbox">
        <span class="select-none">Lunas</span>
    </label>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="checked" value="1" @checked(request('checked') == '1') class="toggle-checkbox">
        <span class="select-none">Checked</span>
    </label>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="cancel" value="1" @checked(request('cancel') == '1') class="toggle-checkbox">
        <span class="select-none">Batal</span>
    </label>

    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border rounded px-2 py-1.5">
    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border rounded px-2 py-1.5">

    <div class="col-span-full text-right">
        <button class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm">Filter</button>
    </div>
</form>

<div class="bg-white shadow rounded overflow-x-auto">
    <table id="invoiceTable" class="min-w-full text-sm divide-y divide-gray-200">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-3 py-2">ID</th>
                <th class="px-3 py-2">Invoice No</th>
                <th class="px-3 py-2">Platform</th>
                <th class="px-3 py-2">Customer</th>
                <th class="px-3 py-2 text-right">Total</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $inv)
                <tr class="border-t">
                    <td class="px-3 py-2">{{ $inv->id }}</td>
                    <td class="px-3 py-2">{{ $inv->inv_no }}</td>
                    <td class="px-3 py-2">{{ $inv->platform_text ?? 'Offline' }}</td>
                    <td class="px-3 py-2">
                        <div class="font-medium">{{ $inv->company?->name ?? '-' }}</div>
                        <div>{{ $inv->customer->name ?? '-' }}</div>
                        <div class="text-gray-500 text-xs">{{ $inv->customer->address ?? '-' }}</div>
                    </td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($inv->g_total_invoice_amount, 0, ',', '.') }}</td>
                    <td class="px-3 py-2">
                        @if ($inv->cancel)
                            <span class="text-red-600 font-semibold">Batal</span>
                        @elseif ($inv->lunas_at)
                            <span class="text-green-600 font-semibold">Lunas</span>
                        @elseif ($inv->checked_finance_at)
                            <span class="text-yellow-600 font-semibold">Checked</span>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        <a href="{{ route('invoice.show', $inv->id) }}" class="text-blue-600 hover:underline">Lihat</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $invoices->withQueryString()->links() }}
</div>
@endsection

@section('scripts')
<!-- CDN TomSelect & SimpleDatatables -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // TomSelect
        document.querySelectorAll('.tomselect').forEach(el => {
            new TomSelect(el, {
                create: false,
                sortField: { field: "text", direction: "asc" }
            });
        });

        // MiniDataTable
        if (window.simpleDatatables) {
            new simpleDatatables.DataTable("#invoiceTable", {
                perPage: 10,
                labels: {
                    placeholder: "Cari...",
                    perPage: "Data per halaman",
                    noRows: "Tidak ada data",
                    info: "Menampilkan {start} - {end} dari {rows} data",
                }
            });
        }
    });
</script>

<style>
/* Toggle Switch Styling */
.toggle-checkbox {
    appearance: none;
    width: 32px;
    height: 16px;
    background: #d1d5db;
    border-radius: 9999px;
    position: relative;
    cursor: pointer;
    outline: none;
    transition: background 0.3s;
}
.toggle-checkbox:checked {
    background: #2563eb;
}
.toggle-checkbox::before {
    content: "";
    position: absolute;
    width: 14px;
    height: 14px;
    border-radius: 9999px;
    top: 1px;
    left: 1px;
    background: white;
    transition: transform 0.3s;
}
.toggle-checkbox:checked::before {
    transform: translateX(16px);
}
</style>
@endsection
