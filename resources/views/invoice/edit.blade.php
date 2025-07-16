@extends('layouts.app')
@section('title', 'Edit Invoice')

@section('content')
<h1 class="text-lg font-semibold mb-4">Edit Invoice | {{ $invoice->id }} | {{ $invoice->inv_no }}</h1>

<x-alert-success />
<x-alert-error />

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- FORM EDIT INVOICE -->
    <form action="{{ route('invoice.update', $invoice->id) }}" method="POST" class="bg-white rounded shadow p-4">
        @csrf
        @method('PUT')
        @include('invoice.form', ['invoice' => $invoice])
        <div class="mt-4 text-right">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>

    <!-- FORM CANCEL -->
    <div class="bg-white rounded shadow p-4 h-fit">
        @if(!$invoice->cancel)
        <form action="{{ route('invoice.cancel', $invoice->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan invoice ini?')">
            @csrf
            <label class="block mb-1 font-medium">Alasan Pembatalan</label>
            <textarea name="cancel_reason" class="w-full border px-3 py-2 rounded" rows="4" required></textarea>

            <button type="submit" class="mt-3 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 w-full">
                Batalkan Invoice
            </button>
        </form>
        @else
        <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded">
            <strong>Invoice dibatalkan.</strong><br>
            Alasan: {{ $invoice->cancel_reason }}
        </div>
        @endif
    </div>
</div>

{{-- Daftar item produk --}}
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
                    <th class="px-3 py-2 text-center">Aksi</th>
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
                        <td class="px-3 py-2 text-center">
                            <form action="{{ route('invoice-item.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus item ini dari invoice?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot class="bg-gray-50 border-t font-semibold">
                <tr>
                    <td class="px-3 py-2 text-right" colspan="1">Total:</td>
                    <td class="px-3 py-2">{{ number_format($totalQtyOut, 3) }} kg</td>
                    <td class="px-3 py-2"></td>
                    <td class="px-3 py-2">Rp {{ number_format($totalSubtotal) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endif


{{-- Tambah item produk --}}
<div class="bg-white rounded shadow p-4">
    <h2 class="text-md font-semibold mb-3">Tambah Produk ke Invoice</h2>
    @include('invoice.form-item', ['invoice' => $invoice, 'products' => $products])
</div>

@endsection
