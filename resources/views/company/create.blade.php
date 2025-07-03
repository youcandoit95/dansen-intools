@extends('layouts.app')

@section('title', 'Tambah Perusahaan')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">Tambah Perusahaan</h2>

    <x-alert-success />
    <x-alert-error />

    <form action="{{ route('company.store') }}" method="POST">
        @csrf

        @include('company.form', ['company' => null, 'domisiliList' => $domisili])

        <div class="mt-4 flex justify-end">
            <a href="{{ route('company.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Batal</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded ml-2">Simpan</button>
        </div>
    </form>
</div>
@endsection
