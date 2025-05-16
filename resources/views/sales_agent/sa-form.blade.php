{{-- resources/views/sales_agents/form.blade.php --}}

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

<div class="mb-4">
    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
    <input type="text" name="nama" id="nama"
        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-500 @error('nama') border-red-500 @enderror"
        value="{{ old('nama', $salesAgent->nama ?? '') }}">
    @error('nama')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon *</label>
    <input type="text" name="telepon" id="telepon"
        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-500 @error('telepon') border-red-500 @enderror"
        value="{{ old('telepon', $salesAgent->telepon ?? '') }}">
    @error('telepon')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
    <input type="email" name="email" id="email"
        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-500 @error('email') border-red-500 @enderror"
        value="{{ old('email', $salesAgent->email ?? '') }}">
    @error('email')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="domisili" class="block text-sm font-medium text-gray-700 mb-1">Domisili *</label>
    <select name="domisili" id="domisili"
        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-500 @error('domisili') border-red-500 @enderror">
        <option value="">-- Pilih Domisili --</option>
        @foreach ($domisiliList as $id => $nama)
            <option value="{{ $id }}"
                {{ old('domisili', $salesAgent->domisili ?? '') == $id ? 'selected' : '' }}>
                {{ $nama }}
            </option>
        @endforeach
    </select>
    @error('domisili')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
