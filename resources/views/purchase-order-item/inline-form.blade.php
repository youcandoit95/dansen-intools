<div class="mt-10 p-6 bg-white rounded shadow">
  <h2 class="text-lg font-semibold mb-4">Tambah Item Purchase Order</h2>

  <form action="{{ route('purchase-order-item.store', $purchaseOrder->id) }}" method="POST" class="grid grid-cols-12 gap-4 items-end">
    @csrf

    <!-- Produk -->
    <div class="col-span-5">
      <label class="block text-sm mb-1">Produk</label>
      <select name="product_id" id="product_id" class="tom-select w-full border rounded px-3 py-2" required>
        <option value="">-- Pilih Produk --</option>
        @foreach($products as $prod)
          <option value="{{ $prod['id'] }}" data-harga="{{ $prod['harga_beli'] }}">
            {{ $prod['nama'] }}
          </option>
        @endforeach
      </select>
    </div>

    <!-- Qty -->
    <div class="col-span-2">
      <label class="block text-sm mb-1">Qty</label>
      <input type="number" name="qty" id="qty" class="w-full border rounded px-3 py-2" required>
    </div>

    <!-- Harga Beli -->
    <div class="col-span-3">
      <label class="block text-sm mb-1">Harga Beli</label>
      <input type="text" id="harga_beli" name="harga_beli" class="rupiah-input w-full border rounded px-3 py-2 bg-gray-100 text-gray-600" readonly required>
    </div>

    <!-- Subtotal -->
    <div class="col-span-2">
      <label class="block text-sm mb-1">Subtotal</label>
      <input type="text" id="subtotal" class="w-full border rounded px-3 py-2 bg-gray-50 text-gray-800" readonly>
    </div>

    <!-- Submit -->
    <div class="col-span-12 mt-2">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full md:w-auto">
        + Tambah Item
      </button>
    </div>
  </form>

  <!-- Daftar Item -->
  <div class="mt-8">
    <h3 class="text-md font-semibold mb-2">Daftar Item</h3>
    <table class="w-full table-auto border border-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-3 py-2 text-left">Produk</th>
          <th class="px-3 py-2 text-left">Qty</th>
          <th class="px-3 py-2 text-left">Harga Beli</th>
          <th class="px-3 py-2 text-left">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($purchaseOrder->items as $item)
        <tr class="border-t">
          <td class="px-3 py-2">{{ $item->product->nama ?? '-' }}</td>
          <td class="px-3 py-2">{{ $item->qty }}</td>
          <td class="px-3 py-2">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
          <td class="px-3 py-2">Rp {{ number_format($item->qty * $item->harga_beli, 0, ',', '.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('product_id');
    const qtyInput = document.getElementById('qty');
    const hargaInput = document.getElementById('harga_beli');
    const subtotalInput = document.getElementById('subtotal');

    function formatRupiah(angka) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(angka || 0);
    }

    function updateSubtotal() {
      const qty = parseInt(qtyInput.value || 0);
      const harga = parseInt(hargaInput.dataset.raw || 0);
      subtotalInput.value = formatRupiah(qty * harga);
    }

    // Init TomSelect jika belum
    if (!productSelect.classList.contains('tom-selected')) {
      new TomSelect(productSelect, {
        onChange(value) {
          const selected = productSelect.querySelector(`option[value="${value}"]`);
          if (selected && selected.dataset.harga) {
            const harga = parseInt(selected.dataset.harga || '0');
            hargaInput.value = formatRupiah(harga);
            hargaInput.dataset.raw = harga;
            updateSubtotal();
          } else {
            hargaInput.value = '';
            subtotalInput.value = '';
          }
        }
      });
      productSelect.classList.add('tom-selected');
    }

    qtyInput.addEventListener('input', updateSubtotal);
  });
</script>
@endsection
