@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow text-sm">
    <h1 class="text-lg font-semibold mb-4">Detail Stok</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Informasi Stok --}}
        <div>
            <h2 class="text-md font-semibold mb-2">Informasi Stok</h2>
            <table class="w-full border text-sm">
                <tr>
                    <td class="font-medium py-1 pr-3">ID</td>
                    <td>: {{ $stok->id }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Produk</td>
                    <td>: {{ $stok->product->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Barcode</td>
                    <td>: <span class="font-mono">{{ $stok->barcode_stok }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ $stok->barcode_stok }}')" class="text-xs bg-gray-200 rounded px-2 py-1 ml-2">Salin</button>
                    </td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Berat Stok Masuk</td>
                    <td>: {{ number_format($stok->berat_kg, 3) }} kg</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Cabang</td>
                    <td>: {{ $stok->cabang->nama_cabang ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Tanggal Masuk</td>
                    <td>: {{ \Carbon\Carbon::parse($stok->created_at)->translatedFormat('l, d F Y H:i') }}</td>
                </tr>
                @if($stok->destroy_type)
                <tr>
                    <td class="font-medium py-1 pr-3 text-red-600">Status</td>
                    <td>: <span class="text-red-600">{{ $stok->destroy_keterangan }}</span> oleh {{ $stok->destroyer->name ?? '-' }}</td>
                </tr>
                @endif
            </table>
        </div>

        {{-- Informasi Inbound --}}
        <div>
            <h2 class="text-md font-semibold mb-2">Informasi Inbound</h2>
            @if($stok->inbound)
            <table class="w-full border text-sm">
                <tr>
                    <td class="font-medium py-1 pr-3">No Surat Jalan</td>
                    <td>: {{ $stok->inbound->no_surat_jalan }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($stok->inbound->created_at)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Supplier</td>
                    <td>: {{ $stok->inbound->supplier->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 pr-2">Dibuat oleh</td>
                    <td class="py-1">: {{ $stok->inbound->createdBy->username ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 pr-2">Disubmit oleh</td>
                    <td class="py-1">: {{ $stok->inbound->submittedBy->username ?? '-' }}</td>
                </tr>

            </table>
            @else
            <p class="text-gray-500 italic">Tidak terkait dengan data inbound.</p>
            @endif
        </div>

        {{-- Informasi PO --}}
        <div class="md:col-span-2">
            <h2 class="text-md font-semibold mb-2">Informasi Purchase Order</h2>
            @if($stok->inbound && $stok->inbound->purchaseOrder)
            <table class="w-full border text-sm">
                <tr>
                    <td class="font-medium py-1 pr-3">No PO</td>
                    <td>: {{ $stok->inbound->purchaseOrder->no_po }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Tanggal PO</td>
                    <td>: {{ \Carbon\Carbon::parse($stok->inbound->purchaseOrder->created_at)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Catatan</td>
                    <td>: {{ $stok->inbound->purchaseOrder->catatan ?? '-' }}</td>
                </tr>
            </table>
            @else
            <p class="text-gray-500 italic">Tidak memiliki Purchase Order.</p>
            @endif
        </div>
    </div>
</div>
@endsection
