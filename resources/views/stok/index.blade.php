@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-lg font-semibold mb-4">Data Stok</h1>

    <form method="GET" class="mb-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 text-sm">
    <select name="cabang_id" class="border px-2 py-1 rounded">
        <option value="">-- Cabang --</option>
        @foreach($cabangs as $cabang)
            <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                {{ $cabang->nama_cabang }}
            </option>
        @endforeach
    </select>

    <select name="product_id" class="border px-2 py-1 rounded">
        <option value="">-- Produk --</option>
        @foreach($products as $prod)
            <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                {{ $prod->nama }}
            </option>
        @endforeach
    </select>

    <input type="number" name="berat_min" step="0.001" class="border px-2 py-1 rounded" placeholder="Berat Min" value="{{ request('berat_min') }}">
    <input type="number" name="berat_max" step="0.001" class="border px-2 py-1 rounded" placeholder="Berat Max" value="{{ request('berat_max') }}">

    <input type="date" name="tanggal_dari" class="border px-2 py-1 rounded" value="{{ request('tanggal_dari') }}">
    <input type="date" name="tanggal_sampai" class="border px-2 py-1 rounded" value="{{ request('tanggal_sampai') }}">

    <select name="destroy_status" class="border px-2 py-1 rounded">
        <option value="">-- Status --</option>
        <option value="active" {{ request('destroy_status') == 'active' ? 'selected' : '' }}>Aktif</option>
        <option value="destroyed" {{ request('destroy_status') == 'destroyed' ? 'selected' : '' }}>Destroyed</option>
    </select>

    <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700">
        Filter
    </button>
</form>


    <table id="stokTable" class="table w-full border text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-3 py-2 text-left">ID</th>
                <th class="px-3 py-2 text-left">Cabang</th>
                <th class="px-3 py-2 text-left">Produk</th>
                <th class="px-3 py-2 text-left">Berat (kg)</th>
                <th class="px-3 py-2 text-left">Destroy Info</th>
                <th class="px-3 py-2 text-left">Tanggal Masuk</th>
                <th class="px-3 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stoks as $stok)
                <tr>
                    <td class="px-3 py-2">{{ $stok->id }}</td>
                    <td class="px-3 py-2">{{ $stok->cabang->nama_cabang ?? '-' }}</td>
                    <td class="px-3 py-2">
                        {{ $stok->product->nama ?? '-' }}<br>
                        <span class="font-mono text-xs">{{ $stok->barcode_stok }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ $stok->barcode_stok }}')" class="btn bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded px-2 py-1">Salin</button>
                    </td>
                    <td class="px-3 py-2">{{ number_format($stok->berat_kg, 3) }}</td>
                    <td class="px-3 py-2">
                        @if($stok->destroy_type)
                            <span class="text-red-600">{{ $stok->destroy_keterangan ?? '-' }}</span><br>
                            oleh: <strong>{{ $stok->destroyer->name ?? '-' }}</strong>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-3 py-2">{{ \Carbon\Carbon::parse($stok->created_at)->translatedFormat('l, d F Y H:i') }}</td>
                    <td class="px-3 py-2">
                        <a href="#" class="btn btn-sm bg-blue-500 text-white px-2 py-1 rounded">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('#stokTable');
        if (table) {
            new simpleDatatables.DataTable(table, {
                perPage: 25,
                perPageSelect: [10, 25, 50, 100],
                searchable: true,
                fixedHeight: false
            });
        }
    });
</script>
@endsection
