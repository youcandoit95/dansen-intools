@extends('layouts.app')

@section('title', 'Edit Sales Agent')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Sales Agent</h2>

    <form action="{{ route('sales-agent.update', $salesAgent) }}" method="POST">
        @csrf
        @method('PUT')

        @include('sales_agent.sa-form')

        <div class="mt-6 flex space-x-3">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                Update
            </button>
            <a href="{{ route('sales-agent.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm">
                Batal / Kembali
            </a>
        </div>
    </form>
</div>
@endsection
