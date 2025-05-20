@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Daftar Supplier</h2>
        <a href="{{ route('suppliers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Tambah
        </a>
    </div>

    <x-alert-success />

    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm" id="miniSupplierTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">#</th>
                    <th class="border px-4 py-2 text-left">Nama Supplier</th>
                    <th class="border px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $i => $supplier)
                <tr>
                    <td class="border px-4 py-2">{{ $i + 1 }}</td>
                    <td class="border px-4 py-2">{{ $supplier->name }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('suppliers.edit', $supplier->id) }}"
                           class="text-blue-600 hover:underline text-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new simpleDatatables.DataTable("#miniSupplierTable", {
            searchable: true,
            perPage: 10,
            labels: {
                placeholder: "Cari...",
                perPage: "{select} data per halaman",
                noRows: "Tidak ada data",
                info: "Menampilkan {start} - {end} dari {rows} data"
            }
        });
    });
</script>
@endsection
