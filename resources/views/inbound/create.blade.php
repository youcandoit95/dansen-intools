@extends('layouts.app')

@section('title', 'Tambah Inbound')

@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Inbound</h1>
<x-alert-success />
<x-alert-error />

@include('inbound.form')
@endsection
