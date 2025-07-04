@extends('layouts.app')
@section('title', 'Daftar Invoice')

@section('content')
<h1 class="text-xl font-semibold mb-4">Daftar Invoice</h1>

<form method="GET" class="bg-white p-4 rounded shadow mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <input type="text" name="inv_no" value="{{ request('inv_no') }}" class="form-input" placeholder="Nomor Invoice">

    <select name="sales_agents_id" class="form-select tomselect">
        <option value="">Pilih Sales Agent</option>
        @foreach ($salesAgents as $agent)
            <option value="{{ $agent->id }}" @selected(request('sales_agents_id') == $agent->id)>{{ $agent->name }}</option>
        @endforeach
    </select>

    <select name="company_id" class="form-select tomselect">
        <option value="">Pilih Perusahaan</option>
        @foreach ($companies as $company)
            <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>{{ $company->name }}</option>
        @endforeach
    </select>

    <select name="customer_id" class="form-select tomselect">
        <option value="">Pilih Customer</option>
        @foreach ($customers as $customer)
            <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>{{ $customer->name }}</option>
        @endforeach
    </select>

    <input type="number" name="min_amount" placeholder="Min Total Invoice" value="{{ request('min_amount') }}" class="form-input">
    <input type="number" name="max_amount" placeholder="Max Total Invoice" value="{{ request('max_amount') }}" class="form-input">

    <select name="platform_id" class="form-select tomselect">
        <option value="">Pilih Platform</option>
        <option value="1" @selected(request('platform_id') == '1')>Tokopedia</option>
        <option value="2" @selected(request('platform_id') == '2')>TiktokShop</option>
        <option value="3" @selected(request('platform_id') == '3')>Shopee</option>
        <option value="4" @selected(request('platform_id') == '4')>Blibli</option>
        <option value="5" @selected(request('platform_id') == '5')>Toco</option>
        <option value="0" @selected(request('platform_id') === '0')>Offline</option>
    </select>

    <select name="lunas" class="form-select">
        <option value="">Status Lunas</option>
        <option value="1" @selected(request('lunas') == '1')>Lunas</option>
        <option value="0" @selected(request('lunas') === '0')>Belum</option>
    </select>

    <select name="checked" class="form-select">
        <option value="">Checked Finance</option>
        <option value="1" @selected(request('checked') == '1')>Sudah</option>
        <option value="0" @selected(request('checked') === '0')>Belum</option>
    </select>

    <select name="cancel" class="form-select">
        <option value="">Status Batal</option>
        <option value="1" @selected(request('cancel') == '1')>Batal</option>
        <option value="0" @selected(request('cancel') === '0')>Aktif</option>
    </select>

    <div class="flex gap-2">
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input w-full">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input w-full">
    </div>

    <div class="col-span-full text-right">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
    </div>
</form>

<div class="bg-white shadow rounded overflow-x-auto">
    <table class="min-w-full text-sm divide-y divide-gray-200">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Invoice No</th>
                <th class="px-4 py-2">Platform</th>
                <th class="px-4 py-2">Customer</th>
                <th class="px-4 py-2 text-right">Total</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $inv)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $inv->id }}</td>
                    <td class="px-4 py-2">{{ $inv->inv_no }}</td>
                    <td class="px-4 py-2">{{ $inv->platform_text }}</td>
                    <td class="px-4 py-2">
                        <div class="font-semibold">{{ $inv->company?->name ?? '-' }}</div>
                        <div>{{ $inv->customer->name ?? '-' }}</div>
                        <div class="text-gray-500 text-xs">{{ $inv->customer->address ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-2 text-right">Rp {{ number_format($inv->g_total_invoice_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">
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
                    <td class="px-4 py-2">
                        <a href="{{ route('invoice.show', $inv->id) }}" class="text-blue-600 hover:underline">Lihat</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $invoices->withQueryString()->links() }}
</div>
@endsection

@section('scripts')
<script>
    new TomSelect(".tomselect");
</script>
@endsection
