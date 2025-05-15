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
        <tbody class="bg-white divide-y divide-gray-200 text-sm">
            <tr>
                <td class="px-4 py-2">Cabang Jakarta</td>
                <td class="px-4 py-2">Jl. Sudirman No.1</td>
                <td class="px-4 py-2">021-12345678</td>
                <td class="px-4 py-2">Budi Santoso</td>
                <td class="px-4 py-2">
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Buka</span>
                </td>
            </tr>
            <tr>
                <td class="px-4 py-2">Cabang Bandung</td>
                <td class="px-4 py-2">Jl. Asia Afrika No.99</td>
                <td class="px-4 py-2">022-9876543</td>
                <td class="px-4 py-2">Siti Aminah</td>
                <td class="px-4 py-2">
                    <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">Tutup</span>
                </td>
            </tr>
            <tr>
                <td class="px-4 py-2">Cabang Surabaya</td>
                <td class="px-4 py-2">Jl. Basuki Rahmat No.88</td>
                <td class="px-4 py-2">031-4445556</td>
                <td class="px-4 py-2">Joko Widodo</td>
                <td class="px-4 py-2">
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Buka</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<!-- Pastikan CDN Simple-DataTables disertakan -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
