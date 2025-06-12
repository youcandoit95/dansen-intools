@extends('layouts.app')

@section('title', isset($purchaseOrder) ? 'Edit Purchase Order' : 'Tambah Purchase Order')

@section('content')
<h1 class="text-xl font-semibold mb-4">
    {{ isset($purchaseOrder) ? 'Edit' : 'Tambah' }} Purchase Order
</h1>

<x-alert-success />
<x-alert-error />

@php
$isReadonly = isset($purchaseOrder) && $purchaseOrder->ajukan_at;
$noPo = old('no_po', $purchaseOrder->no_po ?? '');
$tanggal = old('tanggal', $purchaseOrder->tanggal ?? date('Y-m-d'));
$supplierSelected = old('supplier_id', $purchaseOrder->supplier_id ?? '');
$tanggalPermintaan = old('tanggal_permintaan_dikirim', $purchaseOrder->tanggal_permintaan_dikirim ?? '');
$catatan = old('catatan', $purchaseOrder->catatan ?? '');
@endphp

<form action="{{ isset($purchaseOrder) ? route('purchase-order.update', $purchaseOrder->id) : route('purchase-order.store') }}"
    method="POST"
    class="bg-white p-6 rounded shadow max-w-4xl">

    @csrf
    @if(isset($purchaseOrder))
    @method('PUT')
    @endif

    <!-- No PO -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Nomor PO <span class="text-red-500">*</span>
        </label>

        <div class="flex items-center gap-2">
            <input type="text"
                id="no_po"
                name="no_po"
                class="flex-1 border rounded px-3 py-2 bg-gray-100 {{ isset($purchaseOrder) ? 'cursor-not-allowed text-gray-500' : '' }}"
                value="{{ $noPo }}"
                {{ isset($purchaseOrder) ? 'readonly' : '' }}
                required>

            @if (!isset($purchaseOrder))
            <button type="button"
                id="generate-no-po"
                class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                Generate
            </button>
            @endif
        </div>
    </div>


    <!-- Tanggal -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal PO <span class="text-red-500">*</span></label>
        <input type="date"
            name="tanggal"
            class="w-full border rounded px-3 py-2 {{ $isReadonly ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
            value="{{ $tanggal }}"
            {{ $isReadonly ? 'readonly' : '' }}
            required>

    </div>

    <!-- Supplier -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
        <select name="supplier_id"
            class="tom-select w-full border rounded px-3 py-2 {{ $isReadonly ? 'bg-gray-100 cursor-not-allowed text-gray-500 pointer-events-none' : '' }}"
            {{ $isReadonly ? 'disabled' : '' }}>
            <option value="">-- Pilih Supplier --</option>
            @foreach($suppliers as $sup)
            <option value="{{ $sup->id }}" {{ $supplierSelected == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tanggal Permintaan Dikirim -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Tanggal Permintaan Dikirim <span class="text-red-500">*</span>
        </label>

        <div class="flex items-center gap-2">
            <input type="date"
                id="tanggal_permintaan_dikirim"
                name="tanggal_permintaan_dikirim"
                class="flex-1 border rounded px-3 py-2 {{ $isReadonly ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                value="{{ $tanggalPermintaan }}"
                {{ $isReadonly ? 'readonly' : '' }}
                required>


            @if(!$isReadonly)
            <button type="button" id="set-tanggal-besok" class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                Besok
            </button>
            @endif

        </div>
    </div>


    <!-- Catatan -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
        <textarea name="catatan"
            class="w-full border rounded px-3 py-2 {{ $isReadonly ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
            rows="2"
            {{ $isReadonly ? 'readonly' : '' }}>{{ $catatan }}</textarea>

    </div>

    @if(isset($purchaseOrder) && $purchaseOrder->ajukan_at)
    <div class="mb-4 px-4 py-2 bg-blue-100 text-blue-700 rounded border border-blue-300">
        <strong>PO sudah diajukan</strong> pada
        {{ \Carbon\Carbon::parse($purchaseOrder->ajukan_at)->translatedFormat('l, d F Y, H:i') }}
        oleh {{ optional($purchaseOrder->ajukanBy)->username ?? 'User tidak ditemukan' }}.
    </div>
    @endif

    @if(isset($purchaseOrder) && $purchaseOrder->sendemail_at)
    <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded border border-green-300">
        <strong>PO sudah dikirim email</strong> pada
        {{ \Carbon\Carbon::parse($purchaseOrder->sendemail_at)->translatedFormat('l, d F Y, H:i') }}
        oleh {{ optional($purchaseOrder->sendEmailBy)->username ?? 'User tidak ditemukan' }}.
    </div>
    @endif

    @if (isset($purchaseOrder))
    <div class="mt-4 flex gap-2">
        @if (!$isReadonly)
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ isset($purchaseOrder) ? 'Update' : 'Simpan' }}
        </button>
        @endif


        @if (isset($purchaseOrder) && !$purchaseOrder->ajukan_at)
        {{-- Ajukan --}}
        <a href="{{ route('purchase-order.ajukan', $purchaseOrder->id) }}"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            onclick="return confirm('Apakah Anda yakin ingin mengajukan Purchase Order ini?')">
            Ajukan
        </a>
        @endif

        {{-- Kirim Email --}}
        @if (isset($purchaseOrder) && $purchaseOrder->ajukan_at && !$purchaseOrder->sendemail_at)
        <a href="{{ route('purchase-order.kirim-email', $purchaseOrder->id) }}"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            onclick="return confirm('Apakah Anda yakin ingin mengirim email PO ini?')">
            Kirim Email
        </a>
        @endif

        {{-- Batalkan --}}
        @if (isset($purchaseOrder) && is_null($purchaseOrder->sendemail_at))
        <a href="{{ route('purchase-order.batalkan', $purchaseOrder->id) }}"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
            onclick="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus PO ini?')">
            Batalkan
        </a>
        @endif

        @else
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan
        </button>
        @endif
    </div>



</form>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.getElementById('generate-no-po')?.addEventListener('click', function() {
        const cabangInitial = '{{ session("cabang_initial") ?? "XXX" }}';
        const now = new Date();

        const year = now.getFullYear();
        const mmdd = `${String(now.getMonth() + 1).padStart(2, '0')}${String(now.getDate()).padStart(2, '0')}`;
        const hhmmss = `${String(now.getHours()).padStart(2, '0')}${String(now.getMinutes()).padStart(2, '0')}${String(now.getSeconds()).padStart(2, '0')}`;

        const noPO = `PO/${cabangInitial}/${year}/${mmdd}/${hhmmss}`;
        document.getElementById('no_po').value = noPO;
    });

    document.getElementById('set-tanggal-besok')?.addEventListener('click', function() {
        const besok = new Date();
        besok.setDate(besok.getDate() + 1);
        const yyyy = besok.getFullYear();
        const mm = String(besok.getMonth() + 1).padStart(2, '0');
        const dd = String(besok.getDate()).padStart(2, '0');
        const tanggalBesok = `${yyyy}-${mm}-${dd}`;
        document.getElementById('tanggal_permintaan_dikirim').value = tanggalBesok;
    });
</script>

@if(isset($purchaseOrder))
@include('purchase-order-item.inline-form', ['purchaseOrder' => $purchaseOrder, 'products' => $products])
@endif

@endsection
