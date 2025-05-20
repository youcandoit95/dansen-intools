@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">Tambah Customer</h2>

    <x-alert-success />
    <x-alert-error />

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        @include('customers.form', ['customer' => null, 'salesAgents' => $salesAgents, 'domisiliList' => $domisiliList])

        <div class="mt-4 flex justify-end">
            <a href="{{ route('customers.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Batal</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded ml-2">Simpan</button>
        </div>
    </form>
</div>
@endsection
