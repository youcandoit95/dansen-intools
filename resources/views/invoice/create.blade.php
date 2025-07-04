@extends('layouts.app')
@section('title', 'Buat Invoice')

@section('content')
    <h1 class="text-lg font-semibold mb-4">Buat Invoice</h1>

    <form action="{{ route('invoice.store') }}" method="POST" class="bg-white rounded shadow p-4 max-w-2xl">
        @csrf
        @include('invoice.form', ['invoice' => $invoice])
        <div class="mt-4 text-right">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
@endsection
