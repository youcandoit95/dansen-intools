@extends('layouts.app')

@section('title', 'Master Sales Agent')

@section('content')

<h1 class="text-xl font-semibold mb-4">Master Sales Agent</h1>

<!-- Notifikasi -->
@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 bg-red-100 border border-red-300 text-red-800 text-sm px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif

<!-- Tombol Tambah -->
<div class="mb-4">
    <a href="{{ route('sales-agent.create') }}"
       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Sales Agent
    </a>
</div>

<!-- Tabel Sales Agent -->
<div class="overflow-auto max-w-full bg-white shadow rounded border">
    <table id="salesAgentTable" class="w-full text-sm divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domisili</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesAgents as $agent)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $agent->nama }}</td>
                <td class="px-4 py-2">{{ $agent->telepon }}</td>
                <td class="px-4 py-2">{{ $agent->email ?? '-' }}</td>
                <td class="px-4 py-2">{{ $agent->domisiliRef->nama ?? '-' }}</td>
                <td class="px-4 py-2 text-center space-x-1 whitespace-nowrap">
                    <a href="{{ route('sales-agent.edit', $agent) }}"
                        class="inline-block bg-yellow-500 text-white text-xs px-2 py-1 rounded hover:bg-yellow-600">
                        Edit
                    </a>
                    <form action="{{ route('sales-agent.destroy', $agent) }}" method="POST" class="inline"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="inline-block bg-red-600 text-white text-xs px-2 py-1 rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<!-- Simple DataTables -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector("#salesAgentTable");
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
