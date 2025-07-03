@extends('layouts.app')

@section('title', 'Daftar Perusahaan')

@section('content')

<h1 class="text-xl font-semibold mb-4">Master Perusahaan</h1>

@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('company.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Perusahaan
    </a>
</div>

<div class="overflow-x-auto bg-white shadow border rounded">
    <table class="min-w-full text-sm divide-y divide-gray-200" id="companyTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Domisili</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Telepon</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Email</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Alamat</th>
                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Blacklist</th>
                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($companies as $company)
            <tr>
                <td class="px-4 py-2">{{ $company->nama }}</td>
                <td class="px-4 py-2">{{ $company->domisili->nama ?? '-' }}</td>
                <td class="px-4 py-2">{{ $company->telepon }}</td>
                <td class="px-4 py-2">{{ $company->email }}</td>
                <td class="px-4 py-2">{{ $company->alamat }}</td>
                <td class="px-4 py-2 text-center">
                    @if ($company->blacklist)
                        <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">Ya</span><br>
                        <small class="text-red-600 italic">{{ $company->alasan_blacklist }}</small>
                        <form method="POST" action="{{ route('company.unblacklist', $company->id) }}">
                            @csrf
                            <button class="text-xs mt-1 text-yellow-600 underline">Batalkan</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('company.blacklist', $company->id) }}">
                            @csrf
                            <input type="text" name="alasan_blacklist" class="form-input text-xs mt-1 w-full border rounded px-2 py-1" placeholder="Alasan" required>
                            <button class="bg-red-500 text-white text-xs mt-1 px-2 py-1 rounded hover:bg-red-600">Blacklist</button>
                        </form>
                    @endif
                </td>
                <td class="px-4 py-2 text-center space-x-1 whitespace-nowrap">
                    <a href="{{ route('company.edit', $company->id) }}"
                        class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
                    <form action="{{ route('company.destroy', $company->id) }}" method="POST" class="inline"
                          onsubmit="return confirm('Hapus perusahaan ini?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h2 class="text-lg font-semibold mt-10 mb-4">Perusahaan Terhapus</h2>
<div class="overflow-x-auto bg-white shadow border rounded">
    <table class="min-w-full text-sm divide-y divide-gray-200" id="trashedCompanyTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Telepon</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3 text-center">Blacklist</th>
                <th class="px-4 py-3 text-center">Restore</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($deletedCompanies as $company)
            <tr>
                <td class="px-4 py-2">{{ $company->nama }}</td>
                <td class="px-4 py-2">{{ $company->telepon }}</td>
                <td class="px-4 py-2">{{ $company->email }}</td>
                <td class="px-4 py-2 text-center">
                    @if ($company->blacklist)
                        <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">Ya</span><br>
                        <small class="text-red-600 italic">{{ $company->alasan_blacklist }}</small>
                    @else
                        <span class="bg-gray-300 text-gray-700 text-xs px-2 py-1 rounded">Tidak</span>
                    @endif
                </td>
                <td class="px-4 py-2 text-center">
                    <form action="{{ route('company.restore', $company->id) }}" method="POST"
                          onsubmit="return confirm('Pulihkan perusahaan ini?')">
                        @csrf
                        <button class="bg-blue-600 text-white px-2 py-1 text-xs rounded hover:bg-blue-700">
                            🔄 Restore
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const activeTable = document.querySelector("#companyTable");
        if (typeof simpleDatatables !== 'undefined' && activeTable) {
            new simpleDatatables.DataTable(activeTable, {
                perPage: 10,
                labels: {
                    placeholder: "Cari...",
                    perPage: "Data per halaman",
                    noRows: "Tidak ada data",
                    info: "Menampilkan {start} - {end} dari {rows} data",
                }
            });
        }

        const trashTable = document.querySelector("#trashedCompanyTable");
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
