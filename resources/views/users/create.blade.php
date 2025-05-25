@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Pengguna</h1>
<x-alert-success />
<x-alert-error />

@include('users.form')
@endsection
