@extends('layouts.app')

@section('title', 'Edit Perusahaan')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">Edit Perusahaan</h2>

    <x-alert-success />
    <x-alert-error />

    <form action="{{ route('company.update', $company->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('company.form', ['company' => $company, 'domisiliList' => $domisili])

        <div class="mt-4 flex justify-end">
            <a href="{{ route('company.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded ml-2">Update</button>
        </div>
    </form>
</div>
@endsection
