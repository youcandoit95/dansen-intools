@php
    $isEdit = isset($user);
@endphp

<form action="{{ $isEdit ? route('users.update', $user->id) : route('users.store') }}" method="POST" class="bg-white p-6 rounded shadow max-w-2xl">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <!-- Cabang -->
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Cabang</label>
        <select name="cabang_id" id="cabangSelect" class="tom-select w-full border rounded px-3 py-2">
            <option value="">-- Pilih Cabang --</option>
            @foreach($cabangs as $cabang)
                <option value="{{ $cabang->id }}" {{ old('cabang_id', $user->cabang_id ?? '') == $cabang->id ? 'selected' : '' }}>
                    {{ $cabang->nama_cabang }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Username -->
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Username <span class="text-red-500">*</span></label>
        <input type="text" name="username" id="usernameInput" required value="{{ old('username', $user->username ?? '') }}" class="w-full border rounded px-3 py-2">
    </div>

    <!-- Email -->
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full border rounded px-3 py-2">
    </div>

    <!-- No WA -->
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">No. WA</label>
        <input type="text" name="no_wa" value="{{ old('no_wa', $user->no_wa ?? '') }}" class="w-full border rounded px-3 py-2">
    </div>

    <!-- Password (Create only) -->
    @unless($isEdit)
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
        <input type="password" name="password" required class="w-full border rounded px-3 py-2">
    </div>
    @endunless

    <!-- Checkbox Roles -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-4">
        @foreach(['superadmin', 'manager', 'supervisor', 'staff', 'status'] as $role)
            <label class="inline-flex items-center">
                <input type="checkbox" name="{{ $role }}" value="1" {{ old($role, $user->$role ?? false) ? 'checked' : '' }} class="mr-2">
                {{ ucfirst($role) }}
            </label>
        @endforeach
    </div>

    <!-- Submit -->
    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan
        </button>
    </div>
</form>

<!-- TomSelect -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#cabangSelect');

        const usernameInput = document.getElementById('usernameInput');
        if (usernameInput) {
            usernameInput.addEventListener('input', function () {
                this.value = this.value.replace(/\s/g, ''); // hilangkan spasi
            });
        }
    });
</script>
