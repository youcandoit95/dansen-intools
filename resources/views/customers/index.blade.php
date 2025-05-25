@extends('layouts.app')

@section('title', 'Daftar Customer')

@section('content')
<h1 class="text-xl font-semibold mb-4">Master Customers</h1>

<x-alert-success />
<x-alert-error />

<!-- Tombol Tambah -->
<div class="mb-4">
    <a href="{{ route('customers.create') }}"
        class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Customer
    </a>
</div>

<!-- Tabel Customers -->
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
                <td class="border px-4 py-2">
                    {{ $c->nama }}
                    @if($c->is_blacklisted)
                        <span class="ml-2 text-xs bg-red-600 text-white px-2 py-0.5 rounded">Blacklisted</span>
                    @endif
                </td>
                <td class="border px-4 py-2">{{ $c->no_tlp }}</td>
                <td class="border px-4 py-2">{{ $c->salesAgent->nama ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $c->domisiliRef->nama ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $c->alamat_lengkap }}</td>
                <td class="border px-4 py-2 space-y-1 space-x-1">
                    <a href="{{ route('customers.edit', $c) }}"
                        class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">
                        Edit</a>
                    <form action="{{ route('customers.destroy', $c) }}" method="POST" class="inline"
                        onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">
                            Hapus</button>
                    </form>

                    @if(!$c->is_blacklisted)
                        <button type="button"
                            class="btn-blacklist bg-black text-white px-2 py-1 rounded text-xs hover:bg-gray-800"
                            data-id="{{ $c->id }}" data-nama="{{ $c->nama }}">
                            Blacklist
                        </button>
                    @else
                        <form action="{{ route('customers.whitelist', $c) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin whitelist customer ini?')">
                            @csrf
                            <button type="submit"
                                class="border border-gray-400 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-100">
                                Whitelist
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Blacklist -->
<div id="blacklistModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Blacklist Customer</h2>
        <form id="blacklistForm" method="POST">
            @csrf
            <input type="hidden" name="customer_id" id="modalCustomerId">
            <p class="mb-2 text-sm">Masukkan alasan blacklist untuk <strong id="modalCustomerNama"></strong>:</p>
            <textarea name="alasan_blacklist" id="alasan_blacklist" class="w-full border rounded px-3 py-2 mb-4" rows="3" required></textarea>
            <div class="flex justify-end gap-2">
                <button type="button" id="cancelModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" id="submitBlacklistBtn" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-900">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
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

    const modal = document.getElementById('blacklistModal');
    const form = document.getElementById('blacklistForm');
    const idInput = document.getElementById('modalCustomerId');
    const namaLabel = document.getElementById('modalCustomerNama');
    const cancelBtn = document.getElementById('cancelModal');
    const submitBtn = document.getElementById('submitBlacklistBtn');

    document.querySelectorAll('.btn-blacklist').forEach(button => {
        button.addEventListener('click', function () {
            const customerId = this.dataset.id;
            const customerNama = this.dataset.nama;

            idInput.value = customerId;
            namaLabel.textContent = customerNama;
            form.setAttribute('action', `/customers/${customerId}/blacklist`);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    cancelBtn.addEventListener('click', function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        form.reset();
    });

    submitBtn.addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin ingin blacklist customer ini?',
            text: "Data akan diproses!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, blacklist!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
