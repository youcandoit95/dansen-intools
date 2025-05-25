@extends('layouts.app')

@section('title', 'Daftar Customer')

@section('content')


<h1 class="text-xl font-semibold mb-4">Master Customers</h1>

<!-- Notifikasi -->
<x-alert-success />

<x-alert-error />

<!-- Tombol Tambah -->
<div class="mb-4">
    <a href="{{ route('customers.create') }}"
        class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Customer
    </a>
</div>

<!-- Tabel Sales Agent -->
<div class="overflow-auto max-w-full bg-white shadow rounded border">
    <table id="customerTable" class="w-full text-sm divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Nama</th>
                <th class="border px-4 py-2">No Tlp</th>
                <th class="border px-4 py-2">Sales Agent</th>
                <th class="border px-4 py-2">Domisili</th>
                <th class="border px-4 py-2">Alamat</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $i => $c)
            <tr>
                <td class="border px-4 py-2">{{ $i + 1 }}</td>
                <td class="border px-4 py-2">{{ $c->nama }}</td>
                <td class="border px-4 py-2">{{ $c->no_tlp }}</td>
                <td class="border px-4 py-2">{{ $c->salesAgent->nama ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $c->domisiliRef->nama ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $c->alamat_lengkap }}</td>
                <td class="border px-4 py-2 space-x-2">
                    <a href="{{ route('customers.edit', $c) }}"
                        class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">
                        Edit</a>
                    <form action="{{ route('customers.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<!-- Mini DataTable (gunakan jika ringan, tanpa jQuery) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector("#customerTable");
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
