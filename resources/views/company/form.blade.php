@php
    $isEdit = isset($company);
@endphp

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

{{-- Nama Perusahaan --}}
<div class="mb-4">
    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Perusahaan <span class="text-red-500">*</span></label>
    <input type="text" name="nama" id="nama"
           value="{{ old('nama', $company->nama ?? '') }}"
           class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring"
           required>
</div>

{{-- Domisili --}}
<div class="mb-4">
    <label for="domisili_id" class="block text-sm font-medium text-gray-700">Domisili</label>
    <select name="domisili_id" id="domisili_id"
        class="domisili-select w-full border rounded px-3 py-2">
       <option value="">-- Pilih Domisili --</option>
        @foreach($domisiliList as $dom)
            <option value="{{ $dom->id }}"
                {{ old('domisili_id', $company->domisili_id ?? '') == $dom->id ? 'selected' : '' }}>
                {{ $dom->nama }}
            </option>
        @endforeach
    </select>
</div>

{{-- Telepon --}}
<div class="mb-4">
    <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
    <input type="text" name="telepon" id="telepon"
           value="{{ old('telepon', $company->telepon ?? '') }}"
           class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring">
</div>

{{-- Email --}}
<div class="mb-4">
    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" id="email"
           value="{{ old('email', $company->email ?? '') }}"
           class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring">
</div>

{{-- Alamat --}}
<div class="mb-4">
    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
    <textarea name="alamat" id="alamat" rows="3"
              class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring">{{ old('alamat', $company->alamat ?? '') }}</textarea>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('.domisili-select', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "Cari domisili...",
        });
    });
</script>
