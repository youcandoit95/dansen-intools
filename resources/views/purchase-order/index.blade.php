@extends('layouts.app')
@section('title', 'Daftar Purchase Order')
@section('content')
<div class="p-4">
    <h1 class="text-xl font-semibold mb-4">Purchase Order Re-Stock</h1>

    <x-alert-success />
    <x-alert-error />

    <!-- Tombol Tambah -->
    <div class="mb-4">
        <a href="{{ route('purchase-order.create') }}"
            class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Buat PO
        </a>
    </div>

    <!-- Tabel Purchase Order -->
    <div class="overflow-auto max-w-full bg-white shadow rounded border">
        <table id="purchaseOrderTable" class="table-auto w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">No PO</th>
                    <th class="px-4 py-2 text-left">Supplier</th>
                    <th class="px-4 py-2 text-left">Tanggal PO</th>
                    <th class="px-4 py-2 text-left">Tanggal Ajukan</th>
                    <th class="px-4 py-2 text-left">Tanggal Kirim Email</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $item->no_po }}</td>
                    <td class="px-4 py-2">{{ $item->supplier->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</td>
                    <td class="px-4 py-2">
                        {{ $item->ajukan_at ? \Carbon\Carbon::parse($item->ajukan_at)->translatedFormat('l, d F Y H:i') : '-' }}
                    </td>
                    <td class="px-4 py-2">
                        {{ $item->sendemail_at ? \Carbon\Carbon::parse($item->sendemail_at)->translatedFormat('l, d F Y H:i') : '-' }}
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('purchase-order.edit', $item->id) }}" class="text-blue-600 hover:underline">Lihat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector("#purchaseOrderTable");
        if (typeof simpleDatatables !== 'undefined' && table) {
            new simpleDatatables.DataTable(table, {
                perPage: 10,
                labels: {
                    placeholder: "Cari...",
                    perPage: "Data per halaman",
                    noRows: "Tidak ada data",
                    info: "Menampilkan {start} - {end} dari {rows} data",
                }
            });
        }
    });
</script>
@endsection
