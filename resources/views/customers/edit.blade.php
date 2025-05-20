@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">Edit Customer</h2>

    <x-alert-success />
    <x-alert-error />

    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')

        @include('customers.form', ['customer' => $customer, 'salesAgents' => $salesAgents, 'domisiliList' => $domisiliList])

        <div class="mt-4 flex justify-end">
            <a href="{{ route('customers.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded ml-2">Perbarui</button>
        </div>
    </form>
</div>
@endsection
