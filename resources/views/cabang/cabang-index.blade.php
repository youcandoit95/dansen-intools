@extends('layouts.app')

@section('title', 'Master Cabang')

@section('content')

<h1 class="text-xl font-semibold mb-4">Master Cabang</h1>

<!-- Tombol Tambah -->
<div class="mb-4">
    <a href="#" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Cabang
    </a>
</div>

<!-- Tabel Cabang -->
<div class="overflow-auto max-w-full bg-white shadow rounded border">
    <table id="cabangTable" class="w-full text-sm divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Cabang</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama PIC</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cabangs as $cabang)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $cabang->nama_cabang }}</td>
                <td class="px-4 py-2">{{ $cabang->alamat }}</td>
                <td class="px-4 py-2 text-center">{{ $cabang->telepon }}</td>
                <td class="px-4 py-2">{{ $cabang->nama_pic }}</td>
                <td class="px-4 py-2 text-center">
                    <span class="inline-block px-2 py-1 rounded text-xs font-medium
                        {{ $cabang->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $cabang->status ? 'Buka' : 'Tutup' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection

@section('scripts')
<!-- Pastikan CDN Simple-DataTables disertakan -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector("#cabangTable");
        if (typeof simpleDatatables !== 'undefined' && table) {
            new simpleDatatables.DataTable(table, {
                perPage: 5,
                labels: {
                    placeholder: "Cari...",
                    perPage: "data per halaman",
                    noRows: "Tidak ada data",
                    info: "Menampilkan {start} - {end} dari {rows} data",
                }
            });
        }
    });
</script>
@endsection
