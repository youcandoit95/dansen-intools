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
                <p class="col-span-1 text-gray-500">Alamat Customer</p>
                <p class="col-span-2 font-medium">{{ $invoice->customer->alamat_lengkap ?? '-' }}</p>
            </div>
            <div class="grid grid-cols-3">
                <p class="col-span-1 text-gray-500">No Telepon</p>
                <p class="col-span-2 font-medium">
                    {{ $invoice->customer->no_tlp ?? '-' }}
                    @php
                    $noWa = $invoice->customer?->no_tlp;
                    $waLink = null;
                    if ($noWa && preg_match('/^08\d+$/', $noWa)) {
                    $waLink = 'https://wa.me/' . preg_replace('/^08/', '628', $noWa);
                    } elseif ($noWa && preg_match('/^\+628\d+$/', $noWa)) {
                    $waLink = 'https://wa.me/' . preg_replace('/^\+/', '', $noWa);
                    }
                    @endphp
                    @if ($waLink)
                    <a href="{{ $waLink }}" target="_blank" class="text-blue-600 underline text-sm ml-2">
                        (WhatsApp)
                    </a>
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
                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full w-fit">Lunas ({{ \Carbon\Carbon::parse($invoice->lunas_at)->format('d/m/Y') }})</span>
                    @else
                    <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full w-fit">Belum Lunas</span>
                    @endif
                    @if($invoice->checked_finance_at)
                    <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full w-fit">Checked ({{ \Carbon\Carbon::parse($invoice->checked_finance_at)->format('d/m/Y') }})</span>
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
@if($invoice->items && $invoice->items->count())
<div class="bg-white rounded shadow p-4 mt-6 mb-6">
    <h2 class="text-md font-semibold mb-3">Daftar Produk dalam Invoice</h2>
    <table class="w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 text-left">Produk</th>
                <th class="px-3 py-2 text-left">Qty Out</th>
                <th class="px-3 py-2 text-left">Harga</th>
                <th class="px-3 py-2 text-left">Subtotal</th>
                <th class="px-3 py-2 text-left">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalQtyOut = 0;
            $totalSubtotal = 0;
            @endphp

            @foreach ($invoice->items as $item)
            @php
            $totalQtyOut += $item->qty_outbound;
            $totalSubtotal += $item->total_sell_price;
            @endphp
            <tr class="border-t align-top">
                <td class="px-3 py-2">
                    {{ $item->product->nama ?? '-' }}<br>
                    <span class="text-xs text-gray-500">{{ $item->stok->barcode_stok ?? '-' }}</span>
                </td>
                <td class="px-3 py-2">
                    {{ number_format($item->qty_outbound, 3) }} kg
                    <div class="text-xs text-gray-500 mt-1">
                        In: {{ number_format($item->qty_outbound + $item->waste_kg, 3) }} kg<br>
                        Waste: {{ number_format($item->waste_kg, 3) }} kg
                    </div>
                </td>
                <td class="px-3 py-2">Rp {{ number_format($item->sell_price) }}</td>
                <td class="px-3 py-2">Rp {{ number_format($item->total_sell_price) }}</td>
                <td class="px-3 py-2">{{ $item->note }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot class="bg-gray-50 border-t font-semibold">
            <tr>
                <td class="px-3 py-2 text-right" colspan="1">Total:</td>
                <td class="px-3 py-2">{{ number_format($totalQtyOut, 3) }} kg</td>
                <td class="px-3 py-2"></td>
                <td class="px-3 py-2">Rp {{ number_format($totalSubtotal) }}</td>
                <td class="px-3 py-2"></td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@endsection
