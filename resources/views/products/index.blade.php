@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')

<h1 class="text-xl font-semibold mb-4">Master Produk</h1>

@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="flex items-center justify-between mb-4">
    <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Produk
    </a>

    <a href="{{ route('products.export') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        📥 Export Excel
    </a>
</div>

<div class="overflow-x-auto bg-white shadow border rounded">
    <table class="min-w-full text-sm divide-y divide-gray-200" id="productTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Barcode</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Bagian Daging</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">MBS</th>
                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($products as $product)
            <tr>
                <td class="px-4 py-2">{{ $product->barcode }}</td>
                <td class="px-4 py-2">{{ $product->nama }}</td>
                <td class="px-4 py-2">{{ $product->brand_label }}</td>
                <td class="px-4 py-2">{{ $product->kategori_label }}</td>
                <td class="px-4 py-2">{{ $product->bagianDaging->nama ?? '-' }}</td>
                <td class="px-4 py-2">
                    @if ($product->mbs)
                        {{ $product->mbs->bms }}  ({{ $product->mbs->a_grade }})
                    @else
                        -
                    @endif
                </td>
                <td class="px-4 py-2 text-center">
                    <form action="{{ route('products.toggle-status', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-xs px-2 py-1 rounded {{ $product->status ? 'bg-green-600 text-white' : 'bg-gray-400 text-white' }}">
                            {{ $product->status ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </td>
                <td class="px-4 py-2 text-center space-x-1 whitespace-nowrap">
                    <a href="{{ route('products.edit', $product) }}"
                        class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                            onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h2 class="text-lg font-semibold mt-10 mb-4">Produk Terhapus</h2>
<div class="overflow-x-auto bg-white shadow border rounded">
    <table class="min-w-full text-sm divide-y divide-gray-200" id="trashedProductTable">
        <!-- table head dan body -->
        @foreach ($trashedProducts as $product)
        <tr>
            <td>{{ $product->barcode }}</td>
            <td>{{ $product->nama }}</td>
            <td>{{ $product->brand_label }}</td>
            <td>{{ $product->kategori_label }}</td>
            <td>{{ $product->bagianDaging->nama ?? '-' }}</td>
            <td>
                @if ($product->mbs)
                    {{ $product->mbs->bms }} ({{ $product->mbs->a_grade }})
                @else
                    -
                @endif
            </td>
            <td class="text-center">
                <form action="{{ route('products.restore', $product->id) }}" method="POST"
                      onsubmit="return confirm('Pulihkan produk ini?')">
                    @csrf
                    <button type="submit"
                        class="bg-blue-600 text-white px-2 py-1 text-xs rounded hover:bg-blue-700">
                        🔄 Restore
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection

@section('scripts')
<!-- Mini Table Features via Simple-DataTables (jika mau) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector("#productTable");
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

        const trashTable = document.querySelector("#trashedProductTable");
        if (typeof simpleDatatables !== 'undefined' && trashTable) {
            new simpleDatatables.DataTable(trashTable, {
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
