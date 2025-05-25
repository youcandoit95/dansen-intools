@extends('layouts.app')

@section('title', 'Tambah Harga Customer')

@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Harga Customer</h1>

<x-alert-success />
<x-alert-error />

@include('customer_prices.form')
@endsection
