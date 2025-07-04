@extends('layouts.app')
@section('title', 'Detail Invoice')

@section('content')
<h1 class="text-lg font-semibold mb-4">Detail Invoice #{{ $invoice->inv_no }}</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">Informasi Invoice</h2>
        <p><strong>Invoice No:</strong> {{ $invoice->inv_no }}</p>
        <p><strong>Tanggal Transaksi:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_transaction_date)->translatedFormat('d F Y') }}</p>
        <p><strong>Perusahaan:</strong> {{ $invoice->company->nama ?? '-' }}</p>
        <p><strong>Customer:</strong> {{ $invoice->customer->nama ?? '-' }}</p>
        <p><strong>Sales Agent:</strong> {{ $invoice->salesAgent->nama ?? '-' }}</p>
        <p><strong>Platform:</strong> {{ $invoice->platform_text ?? 'Offline' }}</p>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">Ringkasan</h2>
        <p><strong>Total Invoice:</strong> Rp {{ number_format($invoice->g_total_invoice_amount, 0, ',', '.') }}</p>
        <p><strong>Status:</strong>
            @if ($invoice->cancel)
                <span class="text-red-600 font-semibold">Batal</span>
            @elseif ($invoice->lunas_at)
                <span class="text-green-600 font-semibold">Lunas</span>
            @elseif ($invoice->checked_finance_at)
                <span class="text-yellow-600 font-semibold">Checked</span>
            @else
                <span class="text-gray-600">Belum Diproses</span>
            @endif
        </p>
    </div>
</div>

<div class="bg-white shadow rounded overflow-x-auto">
    <h2 class="font-semibold px-4 pt-4">Detail Produk</h2>
    <table class="min-w-full text-sm mt-2">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Produk</th>
                <th class="px-4 py-2">Stok ID</th>
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
