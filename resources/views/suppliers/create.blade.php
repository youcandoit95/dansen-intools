@extends('layouts.app')

@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')

@section('content')
<div class="max-w-lg mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">{{ isset($supplier) ? 'Edit' : 'Tambah' }} Supplier</h2>

    <x-alert-error />

    <form action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}"
          method="POST">
        @csrf
        @if(isset($supplier)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-sm mb-1">Nama Supplier</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2"
                   value="{{ old('name', $supplier->name ?? '') }}" required>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('suppliers.index') }}" class="text-gray-600 px-4 py-2">Batal</a>
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ isset($supplier) ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
@endsection
