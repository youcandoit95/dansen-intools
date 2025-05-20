@extends('layouts.app')

@section('title', 'Tambah Harga Produk')

@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Harga Produk</h1>

<form action="{{ route('product-prices.store') }}" method="POST" class="bg-white p-6 rounded shadow max-w-2xl">
    @csrf

    @include('product_prices.form', ['productPrice' => null])

    <div class="flex justify-end mt-4">
        <a href="{{ route('product-prices.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Batal</a>
        <button type="submit" class="ml-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
    </div>
</form>
@endsection
