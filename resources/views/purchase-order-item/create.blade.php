@extends('layouts.app')

@section('title', 'Tambah Item Purchase Order')

@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Item PO</h1>

<x-alert-success />
<x-alert-error />

@include('purchase_order_items.form')
@endsection
