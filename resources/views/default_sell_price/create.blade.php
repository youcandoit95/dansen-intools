@extends('layouts.app')

@section('title', 'Tambah Default Sell Price')

@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Harga Jual Default</h1>

<x-alert-error />

<div class="mb-4">
    <a href="{{ route('default-sell-price.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        ← Kembali ke daftar harga
    </a>
</div>

<form action="{{ route('default-sell-price.store') }}" class="bg-white p-6 rounded shadow max-w-2xl" method="POST">
    @csrf
    @include('default_sell_price.form')
</form>
@endsection
