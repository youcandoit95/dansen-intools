@extends('layouts.app')

@section('title', 'Tambah Pengaturan Harga Jual (%)')

@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Pengaturan Persentase Harga Jual</h1>

<form action="{{ route('sell-price-settings.store') }}" method="POST" class="bg-white rounded shadow p-6 max-w-md space-y-4">
    @csrf

    @php
        $defaults = [
            'online' => $lastSetting->online ?? '',
            'offline' => $lastSetting->offline ?? '',
            'reseller' => $lastSetting->reseller ?? '',
            'resto' => $lastSetting->resto ?? '',
            'bottom' => $lastSetting->bottom ?? ''
        ];
    @endphp

    @foreach (['online' => 'Online', 'offline' => 'Offline', 'reseller' => 'Reseller', 'resto' => 'Resto', 'bottom' => 'Bottom'] as $key => $label)
        <div>
            <label class="block font-medium text-sm mb-1">{{ $label }} (%) <span class="text-red-500">*</span></label>
            <input type="number" name="{{ $key }}" min="0" max="100"
                value="{{ old($key, $defaults[$key]) }}"
                class="w-full border rounded px-3 py-2 @error($key) border-red-500 @enderror" required>
            @error($key)
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endforeach

    <div class="text-right pt-4">
        <a href="{{ route('sell-price-settings.index') }}" class="text-gray-600 hover:underline mr-4">Batal</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
    </div>
</form>
@endsection
