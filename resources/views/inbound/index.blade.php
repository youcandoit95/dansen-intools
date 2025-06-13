@extends('layouts.app')

@section('title', 'Data Inbound / Surat Jalan')

@section('content')
<h1 class="text-xl font-semibold mb-4">Daftar Inbound</h1>

<x-alert-success />
<x-alert-error />

<div class="mb-4">
    <a href="{{ route('inbound.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Inbound
    </a>
</div>

<div class="overflow-auto max-w-full bg-white shadow rounded border">
    <table id="inboundTable" class="table-auto w-full border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">No</th>
                <th class="px-4 py-2 text-left">No Surat Jalan</th>
                <th class="px-4 py-2 text-left">Supplier</th>
                <th class="px-4 py-2 text-left">Tanggal Submit</th>
                <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $inbound)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $inbound->no_surat_jalan }}</td>
                <td class="px-4 py-2">{{ $inbound->supplier->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $inbound->submitted_at ? \Carbon\Carbon::parse($inbound->submitted_at)->translatedFormat('d M Y H:i') : '-' }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('inbound.edit', $inbound->id) }}" class="text-blue-600 hover:underline">Lihat</a>
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
    const table = document.querySelector("#inboundTable");
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
