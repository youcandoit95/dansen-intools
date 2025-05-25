@extends('layouts.app')

@section('title', 'Edit Harga Customer')

@section('content')
<h1 class="text-xl font-semibold mb-4">Edit Harga Customer</h1>

<x-alert-success />
<x-alert-error />

@include('customer_prices.form', ['price' => $price])
@endsection
