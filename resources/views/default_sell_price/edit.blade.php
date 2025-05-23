@extends('layouts.app')

@section('title', 'Edit Harga Jual Default')

@section('content')
<h1 class="text-xl font-semibold mb-4">Edit Harga Jual Default</h1>

<x-alert-error />

<form action="{{ route('default-sell-price.update', $defaultSellPrice) }}" class="bg-white p-6 rounded shadow max-w-2xl" method="POST">
    @csrf
    @method('PUT')
    @include('default_sell_price.form')
</form>
@endsection
