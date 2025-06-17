@php
$create = !isset($inbound);
@endphp

<div class="flex flex-col md:flex-row gap-6 items-start">
    <form action="{{ $create ? route('inbound.store') : route('inbound.update', $inbound->id) }}"
        method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded shadow w-full md:w-1/3">
        @csrf
        @if (!$create)
        @method('PUT')
        @endif

        <!-- Fields seperti sebelumnya -->
        @include('inbound.fields', ['inbound' => $inbound ?? null, 'suppliers' => $suppliers, 'purchaseOrders' => $purchaseOrders])

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ $create ? 'Simpan' : 'Update' }}
        </button>

        @if(isset($inbound) && !$inbound->submitted_at)
        <a href="{{ route('inbound.submit', $inbound->id) }}"
            onclick="return confirm('Apakah Anda yakin ingin mensubmit inbound ini?')"
            class="btn bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Submit
        </a>
        @endif

    </form>

    <!-- PO Info Sidebar -->
    <div id="po-info" class="bg-white p-6 rounded shadow md:w-2/3 hidden">
        <h2 class=" font-semibold mb-2">Informasi Purchase Order</h2>

        <table class="w-full text-sm">
            <tbody id="po-table-info" class="text-gray-800 align-top space-y-1">
                <!-- Isi via JS -->
            </tbody>
        </table>

        <div class="mt-4">
            <h4 class="font-semibold mb-1">Detail Produk:</h4>
            <div class="overflow-auto rounded border border-gray-200">
                <table class="w-full text-sm table-auto">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-3 py-2">Produk</th>
                            <th class="px-3 py-2">Qty</th>
                            <th class="px-3 py-2">Harga Beli</th>
                            <th class="px-3 py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="po-item-list"></tbody>
                    <tfoot>
                        <tr class="bg-gray-50 font-semibold border-t">
                            <td class="px-3 py-2 text-right" colspan="1">Total</td>
                            <td class="px-3 py-2" id="po-total-qty">0</td>
                            <td class="px-3 py-2 text-right">—</td>
                            <td class="px-3 py-2" id="po-total">Rp 0</td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal Tambah Stok Masuk -->
<div id="modalStokMasuk" class="fixed inset-0 z-50 bg-black bg-opacity-30 hidden items-center justify-center">
  <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
    <h3 class="text-lg font-semibold mb-4">Tambah Stok Masuk</h3>

    <form id="formStokMasuk" onsubmit="submitStokMasuk(event)">
      <div class="mb-3">
        <label class="block text-sm mb-1">Produk</label>
        <select id="inputProdukId" class="w-full border px-3 py-2 rounded" required></select>
      </div>
      <div class="mb-3">
        <label class="block text-sm mb-1">Kategori</label>
        <select id="inputKategori" class="w-full border px-3 py-2 rounded" required>
          <option value="">-- Pilih --</option>
          <option value="1">Loaf/kg</option>
          <option value="2">Cut/kg</option>
          <option value="3">Pcs/pack</option>
          <option value="99">Waste</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm mb-1">Berat (kg)</label>
        <input type="number" step="0.001" min="0" id="inputBerat" class="w-full border px-3 py-2 rounded" required>
      </div>

      <div class="mt-5 flex justify-end gap-2">
        <button type="button" onclick="closeModalStokMasuk()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const datatable = new simpleDatatables.DataTable("#stokMasukTable");
  });
</script>
