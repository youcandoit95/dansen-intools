@extends('layouts.app')

@section('title', 'Daftar Harga Produk')

@section('content')

<h1 class="text-xl font-semibold mb-4">Master Harga Produk (Harga Beli)</h1>

@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('product-prices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Harga
    </a>
</div>

<div class="overflow-x-auto bg-white shadow border rounded">
    <table class="min-w-full text-sm divide-y divide-gray-200" id="productPriceTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($productPrices as $pp)
            <tr>
                <td class="px-4 py-2">{{ $pp->product->nama }}</td>
                <td class="px-4 py-2">{{ $pp->supplier->name }}</td>
                <td class="px-4 py-2">Rp {{ number_format($pp->harga, 0, ',', '.') }}</td>
                <td class="px-4 py-2 text-center space-x-1 whitespace-nowrap">
                    <a href="{{ route('product-prices.edit', $pp) }}"
                        class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
                    <form action="{{ route('product-prices.destroy', $pp) }}" method="POST" class="inline"
                          onsubmit="return confirm('Hapus harga ini?')">
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
        const table = document.querySelector("#productPriceTable");
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
