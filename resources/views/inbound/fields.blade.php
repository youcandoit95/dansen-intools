<div class="mb-4">
    <label class="block text-sm mb-1 font-medium">No Surat Jalan <span class="text-red-500">*</span></label>
    <input type="text" name="no_surat_jalan" class="w-full border rounded px-3 py-2"
        value="{{ old('no_surat_jalan', $inbound->no_surat_jalan ?? '') }}" required>
</div>

<div class="mb-4">
    <label class="block text-sm mb-1 font-medium">No PO</label>
    <select name="purchase_order_id" class="w-full border rounded px-3 py-2">
        <option value="">-- Tidak Ada --</option>
        @foreach($purchaseOrders as $po)
        <option value="{{ $po->id }}"
            {{ old('purchase_order_id', $inbound->purchase_order_id ?? '') == $po->id ? 'selected' : '' }}>
            {{ $po->no_po }}
        </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block text-sm mb-1 font-medium">Supplier <span class="text-red-500">*</span></label>
    <select id="supplier_id" name="supplier_id" class="w-full border rounded px-3 py-2" required>
        <option value="">-- Pilih Supplier --</option>
        @foreach($suppliers as $sup)
        <option value="{{ $sup->id }}"
            {{ old('supplier_id', $inbound->supplier_id ?? '') == $sup->id ? 'selected' : '' }}>
            {{ $sup->name }}
        </option>
        @endforeach
    </select>
</div>

<!-- Modal Preview Gambar -->
<div id="imagePreviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-70">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 p-4 relative">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold text-gray-700">Preview Gambar</h2>
            <button onclick="closeImageModal()"
                class="text-gray-600 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div class="border rounded p-2 bg-gray-50">
            <img id="modalPreviewImage" src="" alt="Preview" class="w-full max-h-[70vh] object-contain rounded" />
        </div>
        <div class="mt-4 text-right">
            <button onclick="closeImageModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Tutup
            </button>
        </div>
    </div>
</div>


<!-- Upload Foto -->
@for($i = 1; $i <= 3; $i++)
    @php
        $fotoField = 'foto_surat_jalan_' . $i;
        $filename = $inbound->$fotoField ?? null;
    @endphp

    <div class="mb-6 border p-4 rounded bg-gray-50">
        <label class="block text-sm font-semibold mb-2">
            Foto Surat Jalan {{ $i }} {!! $i == 1 ? '<span class="text-red-500">*</span>' : '' !!}
        </label>

        {{-- Upload Baru --}}
        <div class="mb-2">
            <label class="block text-xs text-gray-600 mb-1">Upload File Baru</label>
            <input type="file" name="foto_surat_jalan_{{ $i }}" class="w-full" {{ $i == 1 ? 'required' : '' }}>
        </div>

        {{-- File Lama --}}
        @if (!empty($filename))
            <div class="mt-3 border border-red-400 bg-red-50 p-3 rounded text-red-700 relative">
                <label class="block text-xs font-medium mb-1 text-red-700">File Lama:</label>
                <img src="{{ route('inbound.surat-jalan.file', ['inbound' => $inbound->id, 'filename' => basename($filename)]) }}"
                     alt="Foto Surat Jalan {{ $i }}"
                     class="rounded border cursor-pointer max-h-[200px] object-contain"
                     onclick="openImageModal(this.src)">

                <a href="{{ route('inbound.hapus-foto', ['inbound' => $inbound->id, 'field' => $fotoField]) }}"
                   onclick="return confirm('Hapus gambar lama ini?')"
                   class="absolute top-2 right-2 text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                    Hapus
                </a>
            </div>
        @endif
    </div>
@endfor



    @section('scripts')
    <script>
        function openImageModal(src) {
            document.getElementById('modalPreviewImage').src = src;
            document.getElementById('imagePreviewModal').classList.remove('hidden');
            document.getElementById('imagePreviewModal').classList.add('flex');
        }

        function closeImageModal() {
            document.getElementById('modalPreviewImage').src = '';
            document.getElementById('imagePreviewModal').classList.add('hidden');
            document.getElementById('imagePreviewModal').classList.remove('flex');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Trigger API jika purchase_order_id sudah terisi (saat edit)
            const purchaseOrderSelect = document.querySelector('select[name="purchase_order_id"]');
            if (purchaseOrderSelect.value) {
                purchaseOrderSelect.dispatchEvent(new Event('change'));
            }


            for (let i = 1; i <= 3; i++) {
                const input = document.querySelector(`input[name="foto_surat_jalan_${i}"]`);
                const preview = document.createElement('img');
                const container = document.createElement('div');

                preview.classList.add('rounded', 'border', 'block', 'cursor-pointer', 'mt-2');
                preview.style.display = 'none';
                preview.style.maxHeight = '250px';
                preview.style.objectFit = 'contain';

                preview.addEventListener('click', function() {
                    openImageModal(preview.src);
                });

                container.appendChild(preview);
                input.parentNode.appendChild(container);

                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = '';
                        preview.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <script>
        document.querySelector('select[name="purchase_order_id"]').addEventListener('change', function() {
            const poId = this.value;
            const container = document.getElementById('po-info');
            const tableInfo = document.getElementById('po-table-info');
            const list = document.getElementById('po-item-list');
            const totalCell = document.getElementById('po-total');
            const supplierSelect = document.getElementById('supplier_id');

            if (!poId) {
                container.classList.add('hidden');
                tableInfo.innerHTML = '';
                list.innerHTML = '';
                totalCell.textContent = 'Rp 0';
                supplierSelect.classList.remove('bg-gray-100', 'text-gray-500', 'pointer-events-none');
                supplierSelect.value = "";
                return;
            }

            fetch(`/inbound/api/po-detail/${poId}`)
                .then(res => res.json())
                .then(data => {
                    container.classList.remove('hidden');



                    // Set supplier value dari PO
                    if (data.supplier_id) {
                        supplierSelect.value = data.supplier_id;

                        // Simulasikan readonly: tambahkan kelas pointer-events-none dan background
                        supplierSelect.classList.add('bg-gray-100', 'text-gray-500', 'pointer-events-none');
                    }


                    tableInfo.innerHTML = `
                <tr><td class="py-1 pr-2 w-32">No PO</td><td class="py-1">: ${data.no_po}</td></tr>
                <tr><td class="py-1 pr-2">Supplier</td><td class="py-1">: ${data.supplier}</td></tr>
                <tr><td class="py-1 pr-2">Tanggal</td><td class="py-1">: ${data.tanggal}</td></tr>
                <tr><td class="py-1 pr-2">Catatan</td><td class="py-1">: ${data.catatan || '-'}</td></tr>
            `;

                    let total = 0;
                    let totalQty = 0;

                    list.innerHTML = data.items.map(item => {
                        const qty = item.qty || 0;
                        const harga = item.harga || 0;
                        const subtotal = qty * harga;
                        total += subtotal;
                        totalQty += qty;

                        return `
        <tr class="border-t">
            <td class="px-3 py-2">${item.produk}</td>
            <td class="px-3 py-2">${qty}</td>
            <td class="px-3 py-2">Rp ${Number(harga).toLocaleString('id-ID')}</td>
            <td class="px-3 py-2">Rp ${subtotal.toLocaleString('id-ID')}</td>
        </tr>
    `;
                    }).join('');

                    totalCell.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    document.getElementById('po-total-qty').textContent = totalQty;

                })
                .catch(() => {
                    container.classList.add('hidden');
                    tableInfo.innerHTML = '';
                    list.innerHTML = '';
                    totalCell.textContent = 'Rp 0';
                });
        });
    </script>



    @endsection
