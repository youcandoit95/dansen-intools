@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<h1 class="text-xl font-semibold mb-4">Daftar Pengguna</h1>
<x-alert-success />
<x-alert-error />

<div class="mb-4">
    <a href="{{ route('users.create') }}"
       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
       + Tambah Pengguna
    </a>
</div>

<div class="bg-white p-4 rounded shadow mb-6">
    <h2 id="active" class="text-lg font-semibold mb-2">Pengguna Aktif</h2>
    <table class="min-w-full text-sm border" id="userTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-1">Username</th>
                <th class="border px-2 py-1">Cabang</th>
                <th class="border px-2 py-1">Email</th>
                <th class="border px-2 py-1">WA</th>
                <th class="border px-2 py-1">SA</th>
                <th class="border px-2 py-1">Mgr</th>
                <th class="border px-2 py-1">Spv</th>
                <th class="border px-2 py-1">Staff</th>
                <th class="border px-2 py-1">Aktif</th>
                <th class="border px-2 py-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td class="border px-2 py-1">{{ $u->username }}</td>
                <td class="border px-2 py-1">{{ $u->cabang->nama_cabang ?? '-' }}</td>
                <td class="border px-2 py-1">{{ $u->email ?? '-' }}</td>
                <td class="border px-2 py-1">{{ $u->no_wa ?? '-' }}</td>
                @foreach(['superadmin', 'manager', 'supervisor', 'staff', 'status'] as $field)
                    <td class="border px-2 py-1 text-center">
                        <a href="{{ route('users.toggle', ['user' => $u->id, 'field' => $field]) }}"
                           onclick="return confirmToggle(event, '{{ $field }}')"
                           class="text-xs px-2 py-1 rounded {{ $u->$field ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-800' }} hover:opacity-80">
                            {{ $u->$field ? '✓' : '✕' }}
                        </a>
                    </td>
                @endforeach
                <td class="border px-2 py-1 space-y-1 space-x-1">
                    <a href="{{ route('users.edit', $u->id) }}"
                       class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">
                        Edit
                    </a>
                    <button onclick="showResetModal({{ $u->id }}, '{{ $u->username }}')"
                            class="bg-indigo-600 text-white px-2 py-1 rounded text-xs hover:bg-indigo-700">
                        Reset Password
                    </button>
                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-xs px-2 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Reset Password -->
<div id="resetModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow p-6 w-full max-w-md relative">
        <h2 class="text-lg font-semibold mb-4">Reset Password</h2>
        <form id="resetForm" method="POST">
            @csrf
            @method('PUT')
            <p id="resetUsername" class="mb-2 text-sm"></p>
            <input type="password" name="password" placeholder="Password Baru" class="w-full border rounded px-3 py-2 mb-2" required>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full border rounded px-3 py-2 mb-4" required>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeResetModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Reset</button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white p-4 rounded shadow">
    <h2 id="trashed" class="text-lg font-semibold mb-2">Pengguna Terhapus</h2>
    <table class="min-w-full text-sm border" id="trashedTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-1">Username</th>
                <th class="border px-2 py-1">Cabang</th>
                <th class="border px-2 py-1">Email</th>
                <th class="border px-2 py-1">WA</th>
                <th class="border px-2 py-1">Dihapus Pada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trashed as $u)
            <tr>
                <td class="border px-2 py-1">{{ $u->username }}</td>
                <td class="border px-2 py-1">{{ $u->cabang->nama ?? '-' }}</td>
                <td class="border px-2 py-1">{{ $u->email ?? '-' }}</td>
                <td class="border px-2 py-1">{{ $u->no_wa ?? '-' }}</td>
                <td class="border px-2 py-1">{{ $u->deleted_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof simpleDatatables !== 'undefined') {
            new simpleDatatables.DataTable('#userTable');
            new simpleDatatables.DataTable('#trashedTable');
        }
    });

    function confirmDelete(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin menghapus pengguna ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) e.target.submit();
        });
        return false;
    }

    function confirmToggle(e, field) {
        e.preventDefault();
        Swal.fire({
            title: 'Ubah status ' + field + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = e.target.href;
        });
        return false;
    }

    function showResetModal(userId, username) {
        const modal = document.getElementById('resetModal');
        const form = document.getElementById('resetForm');
        const userLabel = document.getElementById('resetUsername');

        form.action = `/users/${userId}/reset-password`;
        userLabel.innerText = `Reset password untuk @${username}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeResetModal() {
        const modal = document.getElementById('resetModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('resetForm').reset();
    }
</script>
@endsection
