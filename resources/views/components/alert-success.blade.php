@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded">
        <strong>Berhasil:</strong>
        <p class="mt-1">{{ session('success') }}</p>
    </div>
@endif
