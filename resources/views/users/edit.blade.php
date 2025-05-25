@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<h1 class="text-xl font-semibold mb-4">Edit Pengguna</h1>
<x-alert-success />
<x-alert-error />

@include('users.form')
@endsection
