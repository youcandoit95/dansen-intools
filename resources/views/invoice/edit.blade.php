@extends('layouts.app')
@section('title', 'Edit Invoice')

@section('content')
    <h1 class="text-lg font-semibold mb-4">Edit Invoice | {{ $invoice->id }} | {{ $invoice->inv_no }}</h1>

    <x-alert-success />
    <x-alert-error />

    <form action="{{ route('invoice.update', $invoice->id) }}" method="POST" class="bg-white rounded shadow p-4 max-w-2xl mb-6">
        @csrf
        @method('PUT')
        @include('invoice.form', ['invoice' => $invoice])
        <div class="mt-4 text-right">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>

    {{-- Tambah item produk --}}
    <div class="bg-white rounded shadow p-4 max-w-2xl">
        <h2 class="text-md font-semibold mb-3">Tambah Produk ke Invoice</h2>
        @include('invoice.form-item', ['invoice' => $invoice, 'products' => $products])
    </div>
@endsection
