<div class="mt-6">
    <h3 class="text-lg font-semibold mb-4">Stok Masuk</h3>

    <div class="flex flex-col md:flex-row gap-6 items-start">

        @if (!isset($inbound) || !$inbound->submitted_at)
            <!-- FORM KIRI -->
            <form action="{{ route('stok.store') }}" method="POST" class="bg-white p-6 rounded shadow w-full md:w-1/3 space-y-4">
                @csrf
                <input type="hidden" name="inbound_id" value="{{ $inbound->id ?? '' }}">

                <div>
                    <label class="text-sm font-medium">Produk</label>
                    <select name="product_id" id="productSelect" class="w-full border px-3 py-2 rounded text-sm" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Kategori</label>
                    <select name="kategori" class="w-full border px-3 py-2 rounded text-sm" required>
                        <option value="1">Loaf/kg</option>
                        <option value="2">Cut/kg</option>
                        <option value="3">Pcs/pack</option>
                        <option value="99">Waste</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Berat (kg)</label>
                    <input type="number" step="0.001" min="0" name="berat_kg"
                        class="w-full border px-3 py-2 rounded text-sm" required>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
                        Tambah Stok Masuk
                    </button>
                </div>
            </form>
        @endif

        <!-- TABEL KANAN -->
        <div class="bg-white p-6 rounded shadow w-full {{ isset($inbound) && $inbound->submitted_at ? '' : 'md:w-2/3' }} overflow-x-auto">
            <h3 class="text-lg font-semibold mb-4">Daftar Stok Masuk</h3>
            @php
    $showHarga = session('superadmin') === true;
    $grandTotalHargaBeli = 0;
@endphp

<table id="tableStokMasuk" class="min-w-full text-sm border border-gray-200">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-3 py-2 text-left">ID</th>
            <th class="px-3 py-2 text-left">Produk</th>
            <th class="px-3 py-2 text-left">Kategori</th>
            <th class="px-3 py-2 text-left">Berat (kg)</th>
            @if($showHarga)
            <th class="px-3 py-2 text-left">Harga Beli</th>
            <th class="px-3 py-2 text-left">Total Harga Beli</th>
            @endif
            <th class="px-3 py-2 text-left">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inbound->stok ?? [] as $stok)
            @php
                $totalHargaBeli = $stok->ss_harga_beli * $stok->berat_kg;
                $grandTotalHargaBeli += $totalHargaBeli;
            @endphp
            <tr class="{{ $stok->barcode_printed ? 'bg-green-100' : '' }}">
                <td class="px-3 py-2">{{ $stok->id ?? '-' }}</td>
                <td class="px-3 py-2">
                    {{ $stok->product->nama ?? '-' }}
                    <div class="text-xs font-mono text-gray-600 flex items-center gap-1 mt-1">

                        <div class="text-xs font-mono text-gray-600 flex items-center gap-2 mt-1">
    <span>{{ $stok->barcode_stok }}</span>
    <button type="button"
        onclick="navigator.clipboard.writeText('{{ $stok->barcode_stok }}')"
        class="btn bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded px-2 py-1"
        title="Salin barcode">
        Salin
    </button>
</div>

                    </div>
                </td>
                <td class="px-3 py-2">{{ $stok->kategori_label }}</td>
                <td class="px-3 py-2">{{ number_format($stok->berat_kg, 3) }}</td>

                @if($showHarga)
                <td class="px-3 py-2">Rp {{ number_format($stok->ss_harga_beli, 0, ',', '.') }}</td>
                <td class="px-3 py-2">Rp {{ number_format($totalHargaBeli, 0, ',', '.') }}</td>
                @endif

                <td class="px-3 py-2 space-x-1">
                    <a href="{{ route('cetak.label', ['nama' => $stok->product->nama, 'barcode' => $stok->barcode_stok]) }}"
                        target="_blank"
                        onclick="refreshAfterOpen(this.href)"
                        class="inline-block px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                        Cetak QR
                    </a>

                    @if (!$inbound->submitted_at)
                    <form action="{{ route('stok.delete', $stok->id) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Hapus stok ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                            Hapus
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>

    @if($showHarga)
    <tfoot>
        <tr class="bg-gray-100 font-semibold">
            <td colspan="5" class="px-3 py-2 text-right">Grand Total Harga Beli</td>
            <td class="px-3 py-2 text-left">Rp {{ number_format($grandTotalHargaBeli, 0, ',', '.') }}</td>
            <td></td>
        </tr>
    </tfoot>
    @endif
</table>

        </div>
    </div>
</div>


<!-- TomSelect -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.min.css" rel="stylesheet">

<!-- DataTable -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Jika inbound belum disubmit, aktifkan TomSelect
    @if(!isset($inbound) || !$inbound->submitted_at)
    new TomSelect("#productSelect", {
        create: false,
        allowEmptyOption: true,
        placeholder: 'Cari produk...',
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
    @endif

    // Inisialisasi Simple-DataTables selalu
    const table = document.querySelector("#tableStokMasuk");
    if (table) {
        new simpleDatatables.DataTable(table, {
            perPage: 15, // default baris
            perPageSelect: [15, 25, 50, 100],
            searchable: true,
            sortable: true,
            fixedHeight: false
        });
    }
});
</script>



<script>
    function refreshAfterOpen(url) {
        window.open(url, '_blank');

        // Tunggu sedikit agar tab sempat terbuka, lalu refresh
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }
</script>
