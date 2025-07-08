@extends('layouts.app')
@section('title', 'Detail Invoice')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-lg font-semibold">Detail Invoice #{{ $invoice->inv_no }}</h1>
    @if(!$invoice->cancel)
        <a href="{{ route('invoice.edit', $invoice->id) }}" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm hover:bg-blue-700">
            Edit Invoice
        </a>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
    <!-- Informasi Invoice -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-3 text-base">Informasi Invoice</h2>
        <div class="grid grid-cols-1 gap-y-2">
            <div class="grid grid-cols-3">
                <p class="col-span-1 text-gray-500">Invoice No</p>
                <p class="col-span-2 font-medium">{{ $invoice->inv_no }}</p>
            </div>
            <div class="grid grid-cols-3">
                <p class="col-span-1 text-gray-500">Tanggal Transaksi</p>
                <p class="col-span-2 font-medium">{{ \Carbon\Carbon::parse($invoice->invoice_transaction_date)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="grid grid-cols-3">
                <p class="col-span-1 text-gray-500">Perusahaan</p>
                <p class="col-span-2 font-medium">
                    {{ $invoice->customer->company->nama ?? '-' }}
                    @if ($invoice->customer->company?->blacklist)
                        <span class="text-red-600 font-semibold">(sedang black list)</span>
                    @endif
                </p>
            </div>
            <div class="grid grid-cols-3">
                <p class="col-span-1 text-gray-500">Customer</p>
                <p class="col-span-2 font-medium">
                    {{ $invoice->customer->nama ?? '-' }}
                    @if ($invoice->customer?->is_blacklisted)
                        <span class="text-red-600 font-semibold">(sedang black list)</span>
                    @endif
                </p>
            </div>
            <div class="grid grid-cols-3">
                <p class="col-span-1 text-gray-500">Sales Agent</p>
                <p class="col-span-2 font-medium">{{ $invoice->customer->salesAgent->nama ?? '-' }}</p>
            </div>
            <div class="grid grid-cols-3">
                <p class="col-span-1 text-gray-500">Platform</p>
                <p class="col-span-2 font-medium">{{ $invoice->platform_text ?? 'Offline' }}</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Status -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-3 text-base">Ringkasan</h2>
        <div class="grid grid-cols-1 gap-y-2">
            <div class="grid grid-cols-3">
                <p class="col-span-1 text-gray-500">Total Invoice</p>
                <p class="col-span-2 font-medium">Rp {{ number_format($invoice->g_total_invoice_amount, 0, ',', '.') }}</p>
            </div>

            <div class="grid grid-cols-3 items-start">
                <p class="col-span-1 text-gray-500">Status</p>
                <div class="col-span-2 flex flex-col gap-1 text-xs font-semibold">
                    @if($invoice->cancel)
                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full w-fit">Dibatalkan</span>
                    @endif
                    @if($invoice->lunas_at)
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full w-fit">Lunas</span>
                    @else
                        <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full w-fit">Belum Lunas</span>
                    @endif
                    @if($invoice->checked_finance_at)
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full w-fit">Checked Finance</span>
                    @else
                        <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full w-fit">Belum Dicek</span>
                    @endif
                </div>
            </div>

            @if($invoice->cancel_reason)
                <div class="grid grid-cols-3">
                    <p class="col-span-1 text-gray-500">Alasan Batal</p>
                    <p class="col-span-2 text-red-800 text-sm">{{ $invoice->cancel_reason }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Detail Produk -->
<div class="bg-white shadow rounded overflow-x-auto">
    <h2 class="font-semibold px-4 pt-4 text-base">Detail Produk</h2>
    <table class="min-w-full text-sm mt-2">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Produk</th>
                <th class="px-4 py-2 text-left">Stok ID</th>
                <th class="px-4 py-2 text-right">Harga Jual</th>
                <th class="px-4 py-2 text-center">Qty</th>
                <th class="px-4 py-2 text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($invoice->items as $item)
                @php $grandTotal += $item->total_sell_price; @endphp
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $item->product->nama ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->stok_id ?? '-' }}</td>
                    <td class="px-4 py-2 text-right">Rp {{ number_format($item->sell_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-center">{{ $item->qty }}</td>
                    <td class="px-4 py-2 text-right">Rp {{ number_format($item->total_sell_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-semibold border-t bg-gray-50">
                <td colspan="5" class="px-4 py-2 text-right">Grand Total</td>
                <td class="px-4 py-2 text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
