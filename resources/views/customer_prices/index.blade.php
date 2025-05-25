@extends('layouts.app')

@section('title', 'Daftar Harga Customer')

@section('content')
<h1 class="text-xl font-semibold mb-4">Harga Khusus Customer</h1>
<x-alert-success />
<x-alert-error />

<div class="mb-4 flex justify-between items-center">
    <a href="{{ route('customer-prices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Harga
    </a>
</div>

<div class="overflow-x-auto bg-white shadow border rounded">
    <table class="min-w-full text-sm divide-y divide-gray-200" id="tableCustomerPrice">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Customer</th>
                <th class="px-4 py-2 text-left">Produk</th>
                <th class="px-4 py-2 text-left">Harga Jual</th>
                <th class="px-4 py-2 text-left">Sales Agent</th>
                <th class="px-4 py-2 text-left">Komisi</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($customerPrices as $item)
                <tr>
                    <td class="px-4 py-2">{{ $item->customer->nama }}</td>
                    <td class="px-4 py-2">{{ $item->product->nama }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $item->salesAgent->nama ?? '-' }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($item->komisi_sales ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-center space-x-1">
                        <a href="{{ route('customer-prices.edit', $item->id) }}"
                            class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
                        <button type="button"
                            data-id="{{ $item->customer->id }}"
                            data-name="{{ $item->customer->nama }}"
                            data-blacklist
                            class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">
                            Blacklist
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Blacklist -->
<div id="modalBlacklist" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center">
    <form method="POST" action="{{ route('customers.blacklist', ['id' => '__ID__']) }}"
          class="bg-white rounded p-6 max-w-md w-full">
        @csrf
        <h2 class="text-lg font-semibold mb-4">Blacklist Customer</h2>
        <p class="mb-2">Masukkan alasan blacklist untuk <strong id="blacklistCustomerName">Customer</strong>:</p>
        <textarea name="alasan" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
        <div class="mt-4 text-right">
            <button type="button" id="cancelBlacklist"
                class="px-4 py-2 bg-gray-400 text-white rounded mr-2">Batal</button>
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Blacklist</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- CDN Simple-DataTables -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modalBlacklist');
        const cancelBtn = document.getElementById('cancelBlacklist');
        const form = modal.querySelector('form');
        const namePlaceholder = document.getElementById('blacklistCustomerName');

        document.querySelectorAll('[data-blacklist]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                form.action = "{{ route('customers.blacklist', ['id' => '__ID__']) }}".replace('__ID__', id);
                namePlaceholder.textContent = name;
                modal.classList.remove('hidden');
            });
        });

        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            form.action = "{{ route('customers.blacklist', ['id' => '__ID__']) }}";
        });

        // Inisialisasi MiniDataTable
        const table = document.getElementById('tableCustomerPrice');
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
