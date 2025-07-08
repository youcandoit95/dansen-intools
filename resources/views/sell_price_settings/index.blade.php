@extends('layouts.app')

@section('title', 'Pengaturan Harga Jual (%)')

@section('content')
<h1 class="text-xl font-semibold mb-4">Pengaturan Persentase Harga Jual</h1>

@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="mb-6">
    <a href="{{ route('sell-price-settings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Pengaturan Baru
    </a>
</div>

@if($currentSetting)
<div class="bg-white rounded shadow p-4 mb-6">
    <h2 class="font-semibold text-base mb-3">Pengaturan Aktif Saat Ini</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border" id="currentTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2">Tanggal</th>
                    <th class="px-3 py-2">Online</th>
                    <th class="px-3 py-2">Offline</th>
                    <th class="px-3 py-2">Reseller</th>
                    <th class="px-3 py-2">Resto</th>
                    <th class="px-3 py-2">Bottom</th>
                    <th class="px-3 py-2">Ditambahkan oleh</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                    <td class="px-3 py-2">{{ \Carbon\Carbon::parse($currentSetting->created_at)->translatedFormat('d M Y H:i') }}</td>
                    <td class="px-3 py-2 text-center">{{ $currentSetting->online }}%</td>
                    <td class="px-3 py-2 text-center">{{ $currentSetting->offline }}%</td>
                    <td class="px-3 py-2 text-center">{{ $currentSetting->reseller }}%</td>
                    <td class="px-3 py-2 text-center">{{ $currentSetting->resto }}%</td>
                    <td class="px-3 py-2 text-center">{{ $currentSetting->bottom }}%</td>
                    <td class="px-3 py-2">{{ $currentSetting->creator->username ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif


@if($history->count())
<div class="bg-white rounded shadow p-4">
    <h2 class="font-semibold text-base mb-3">Riwayat Pengaturan Sebelumnya</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border" id="historyTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-left">Tanggal</th>
                    <th class="px-3 py-2">Online</th>
                    <th class="px-3 py-2">Offline</th>
                    <th class="px-3 py-2">Reseller</th>
                    <th class="px-3 py-2">Resto</th>
                    <th class="px-3 py-2">Bottom</th>
                    <th class="px-3 py-2">Dibuat oleh</th>
                    <th class="px-3 py-2">Dihapus oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $item)
                <tr class="border-t">
                    <td class="px-3 py-2">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y H:i') }}</td>
                    <td class="px-3 py-2 text-center">{{ $item->online }}%</td>
                    <td class="px-3 py-2 text-center">{{ $item->offline }}%</td>
                    <td class="px-3 py-2 text-center">{{ $item->reseller }}%</td>
                    <td class="px-3 py-2 text-center">{{ $item->resto }}%</td>
                    <td class="px-3 py-2 text-center">{{ $item->bottom }}%</td>
                    <td class="px-3 py-2">{{ $item->creator->username ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $item->deleter->username ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
<script>

        document.addEventListener('DOMContentLoaded', () => {
        const historyTable = document.querySelector('#historyTable');
        const currentTable = document.querySelector('#currentTable');

        if (historyTable) new simpleDatatables.DataTable(historyTable);
        if (currentTable) new simpleDatatables.DataTable(currentTable);
    });

</script>
@endsection
