@extends('layouts.app')

@section('title', 'Daftar Harga Customer')

@section('content')
<h1 class="text-xl font-semibold mb-4">Harga Khusus Customer</h1>
<x-alert-success />
<x-alert-error />

<div class="mb-4 flex justify-between items-center">
    <a href="{{ route('customer-prices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Harga
    </a>
</div>

<div class="overflow-x-auto bg-white shadow border rounded">
    <table class="min-w-full text-sm divide-y divide-gray-200" id="tableCustomerPrice">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Customer</th>
                <th class="px-4 py-2 text-left">Produk</th>
                <th class="px-4 py-2 text-left">Harga Jual</th>
                <th class="px-4 py-2 text-left">Sales Agent</th>
                <th class="px-4 py-2 text-left">Komisi</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($customerPrices as $item)
                <tr>
                    <td class="px-4 py-2">{{ $item->customer->nama }}</td>
                    <td class="px-4 py-2">{{ $item->product->nama }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $item->salesAgent->nama ?? '-' }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($item->komisi_sales ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-center space-x-1">
                        <a href="{{ route('customer-prices.edit', $item->id) }}"
                            class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
                        <button type="button"
                            data-id="{{ $item->customer->id }}"
                            data-name="{{ $item->customer->nama }}"
                            data-blacklist
                            class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">
                            Blacklist
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<!-- CDN Simple-DataTables -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi MiniDataTable
        const table = document.getElementById('tableCustomerPrice');
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
