@extends('layouts.app')

@section('title', 'Edit Purchase Order')

@section('content')
<h1 class="text-xl font-semibold mb-4">Edit Purchase Order</h1>

<x-alert-success />
<x-alert-error />

@include('purchase-order.form')

@endsection
