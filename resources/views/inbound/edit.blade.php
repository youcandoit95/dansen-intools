@extends('layouts.app')

@section('title', 'Edit Inbound')

@section('content')
<h1 class="text-xl font-semibold mb-4">Edit Inbound</h1>
<x-alert-success />
<x-alert-error />

@include('inbound.form', ['inbound' => $inbound])
@endsection
