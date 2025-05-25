@php
    $isEdit = isset($customer);
@endphp

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

{{-- Nama --}}
<div class="mb-4">
    <label for="nama" class="block text-sm font-medium text-gray-700">Nama <span class="text-red-500">*</span></label>
    <input type="text" name="nama" id="nama"
           value="{{ old('nama', $customer->nama ?? '') }}"
           class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring"
           required>
</div>

{{-- Sales Agent --}}
<div class="mb-4">
    <label for="sales_agent_id" class="block text-sm font-medium text-gray-700">Sales Agent</label>
    <select name="sales_agent_id" id="sales_agent_id"
            class="sa-select w-full border rounded px-3 py-2">
        <option value="">-- Pilih Sales Agent --</option>
        @foreach($salesAgents as $agent)
            <option value="{{ $agent->id }}"
                {{ old('sales_agent_id', $customer->sales_agent_id ?? '') == $agent->id ? 'selected' : '' }}>
                {{ $agent->nama }} {{-- ✅ Ini benar --}}
            </option>
        @endforeach
    </select>
</div>

{{-- No. Telepon --}}
<div class="mb-4">
    <label for="no_tlp" class="block text-sm font-medium text-gray-700">No. Telepon <span class="text-red-500">*</span></label>
    <input type="text" name="no_tlp" id="no_tlp"
           value="{{ old('no_tlp', $customer->no_tlp ?? '') }}"
           class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring"
           required>
</div>

{{-- Domisili --}}
<div class="mb-4">
    <label for="domisili" class="block text-sm font-medium text-gray-700">Domisili <span class="text-red-500">*</span></label>
    <select name="domisili" id="domisili"
        class="domisili-select w-full border rounded px-3 py-2"
        required>
       <option value="">-- Pilih Domisili --</option>
        @foreach($domisiliList as $dom)
            <option value="{{ $dom->id }}"
                {{ old('domisili', $customer->domisili ?? '') == $dom->id ? 'selected' : '' }}>
                {{ $dom->nama }}
            </option>
        @endforeach
    </select>
</div>

{{-- Alamat Lengkap --}}
<div class="mb-4">
    <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700">Alamat Lengkap <span class="text-red-500">*</span></label>
    <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3"
              class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring"
              required>{{ old('alamat_lengkap', $customer->alamat_lengkap ?? '') }}</textarea>
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

        new TomSelect('.sa-select', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "Cari sales agent...",
        });
    });
</script>
