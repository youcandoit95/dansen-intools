<div class="mt-6">
    <h3 class="text-lg font-semibold mb-4">Tambah Stok Masuk</h3>

    <div class="flex flex-col md:flex-row gap-6 items-start">
        <!-- FORM KIRI -->
        <form action="{{ route('stok.store') }}" method="POST" class="bg-white p-6 rounded shadow w-full md:w-1/3 space-y-4">
            @csrf
            <input type="hidden" name="inbound_id" value="{{ $inbound->id }}">

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

        <!-- TABEL KANAN -->
        <div class="bg-white p-6 rounded shadow w-full md:w-2/3 overflow-x-auto">
            <h3 class="text-lg font-semibold mb-4">Daftar Stok Masuk</h3>
            <table id="tableStokMasuk" class="min-w-full text-sm border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left">ID</th>
                        <th class="px-3 py-2 text-left">Produk</th>
                        <th class="px-3 py-2 text-left">Kategori</th>
                        <th class="px-3 py-2 text-left">Berat (kg)</th>
                        <th class="px-3 py-2 text-left">Barcode</th>
                        <th class="px-3 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inbound->stok ?? [] as $stok)
                    <tr class="{{ $stok->barcode_printed ? 'bg-green-100' : '' }}">
                        <td class="px-3 py-2">{{ $stok->id ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $stok->product->nama ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $stok->kategori_label }}</td>
                        <td class="px-3 py-2">{{ number_format($stok->berat_kg, 3) }}</td>
                        <td class="px-3 py-2 font-mono">{{ $stok->barcode_stok }}</td>
                        <td class="px-3 py-2 space-x-1">
                            <a href="{{ route('cetak.label', ['nama' => $stok->product->nama, 'barcode' => $stok->barcode_stok]) }}"
                                target="_blank"
                                onclick="refreshAfterOpen(this.href)"
                                class="inline-block px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                                Cetak QR
                            </a>


                            <form action="{{ route('stok.delete', $stok->id) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Hapus stok ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
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
        new TomSelect("#productSelect", {
            create: false,
            allowEmptyOption: true,
            placeholder: 'Cari produk...',
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        const table = document.querySelector("#tableStokMasuk");

        if (table) {
            new simpleDatatables.DataTable(table, {
                perPage: 15, // default jumlah baris awal
                perPageSelect: [15, 25, 50, 100], // opsi pilihan per halaman
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
