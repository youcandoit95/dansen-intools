@extends('layouts.app')

@section('title', 'Default Sell Price')

@section('content')
<h1 class="text-xl font-semibold mb-4">Default Sell Price</h1>

<x-alert-success />

<div class="mb-4">
    <a href="{{ route('default-sell-price.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Harga Jual
    </a>
</div>

<div class="overflow-x-auto bg-white shadow border rounded">
    <table class="min-w-full text-sm divide-y divide-gray-200" id="sellPriceTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left">Produk</th>
                <th class="px-4 py-3 text-left">Harga Online</th>
                <th class="px-4 py-3 text-left">Harga Offline</th>
                <th class="px-4 py-3 text-left">Reseller</th>
                <th class="px-4 py-3 text-left">Resto</th>
                <th class="px-4 py-3 text-left">Bottom</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($defaultPrices as $dp)
            <tr>
                <td class="px-4 py-2">
                    <div class="font-medium">{{ $dp->product->nama }}</div>
                    <div class="text-xs text-gray-600">{{ $dp->product->barcode }}</div>
                </td>
                <td class="px-4 py-2">Rp {{ number_format($dp->online_sell_price, 0, ',', '.') }}</td>
                <td class="px-4 py-2">Rp {{ number_format($dp->offline_sell_price, 0, ',', '.') }}</td>
                <td class="px-4 py-2">Rp {{ number_format($dp->reseller_sell_price, 0, ',', '.') }}</td>
                <td class="px-4 py-2">Rp {{ number_format($dp->resto_sell_price, 0, ',', '.') }}</td>
                <td class="px-4 py-2">Rp {{ number_format($dp->bottom_sell_price, 0, ',', '.') }}</td>
                <td class="px-4 py-2 text-center space-x-1">
                    <a href="{{ route('default-sell-price.edit', $dp) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
                    <form action="{{ route('default-sell-price.destroy', $dp) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector("#sellPriceTable");
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
