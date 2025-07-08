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



{{-- Tambah item produk --}}
<div class="bg-white rounded shadow p-4 max-w-3xl">
    <h2 class="text-md font-semibold mb-3">Tambah Produk ke Invoice</h2>
    @include('invoice.form-item', ['invoice' => $invoice, 'products' => $products])
</div>
@endsection
