@extends('layouts.app') {{-- atau layout default kamu --}}
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
        <h1 class="text-5xl font-bold text-red-600">403</h1>
        <p class="text-xl mt-4">Akses Ditolak</p>
        <p class="mt-2 text-gray-600">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ route('dashboard') }}" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
