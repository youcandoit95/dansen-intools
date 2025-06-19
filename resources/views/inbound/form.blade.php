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
@if(isset($inbound) && !$inbound->submitted_at)
@include('inbound._stok_form', ['inbound' => $inbound, 'products' => $products])
@endif
