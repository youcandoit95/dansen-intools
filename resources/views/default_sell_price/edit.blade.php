@extends('layouts.app')

@section('title', 'Edit Harga Jual Default')

@section('content')
<h1 class="text-xl font-semibold mb-4">Edit Harga Jual Default</h1>

<x-alert-error />

<div class="mb-4">
    <a href="{{ route('default-sell-price.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        ← Kembali ke daftar
    </a>
</div>

<form action="{{ route('default-sell-price.update', $defaultSellPrice) }}" method="POST">
    @csrf
    @method('PUT')
    @include('default_sell_price.form')
</form>
@endsection
