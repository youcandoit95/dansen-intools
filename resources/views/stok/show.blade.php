@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow text-sm">
    <h1 class="text-lg font-semibold mb-4">Detail Stok</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Informasi Stok --}}
        <div>
            <h2 class="text-md font-semibold mb-2">Informasi Stok</h2>
            <table class="w-full border text-sm">
                <tr>
                    <td class="font-medium py-1 pr-3">ID</td>
                    <td>: {{ $stok->id }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Produk</td>
                    <td>: {{ $stok->product->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Barcode</td>
                    <td>: <span class="font-mono">{{ $stok->barcode_stok }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ $stok->barcode_stok }}')" class="text-xs bg-gray-200 rounded px-2 py-1 ml-2">Salin</button>
                    </td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Berat Stok Masuk</td>
                    <td>: {{ number_format($stok->berat_kg, 3) }} kg</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Cabang</td>
                    <td>: {{ $stok->cabang->nama_cabang ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Tanggal Masuk</td>
                    <td>: {{ \Carbon\Carbon::parse($stok->created_at)->translatedFormat('l, d F Y H:i') }}</td>
                </tr>
                @if($stok->destroy_type)
                <tr>
                    <td class="font-medium py-1 pr-3 text-red-600">Status</td>
                    <td>: <span class="text-red-600">{{ $stok->destroy_keterangan }}</span> oleh {{ $stok->destroyer->name ?? '-' }}</td>
                </tr>
                @endif
            </table>
        </div>

        {{-- Informasi Inbound --}}
        <div>
            <h2 class="text-md font-semibold mb-2">Informasi Inbound</h2>
            @if($stok->inbound)
            <table class="w-full border text-sm">
                <tr>
                    <td class="font-medium py-1 pr-3">No Surat Jalan</td>
                    <td>: {{ $stok->inbound->no_surat_jalan }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($stok->inbound->created_at)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Supplier</td>
                    <td>: {{ $stok->inbound->supplier->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 pr-2">Dibuat oleh</td>
                    <td class="py-1">: {{ $stok->inbound->createdBy->username ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 pr-2">Disubmit oleh</td>
                    <td class="py-1">: {{ $stok->inbound->submittedBy->username ?? '-' }}</td>
                </tr>

            </table>
            @else
            <p class="text-gray-500 italic">Tidak terkait dengan data inbound.</p>
            @endif
        </div>

        {{-- Informasi PO --}}
        <div class="md:col-span-2">
            <h2 class="text-md font-semibold mb-2">Informasi Purchase Order</h2>
            @if($stok->inbound && $stok->inbound->purchaseOrder)
            <table class="w-full border text-sm">
                <tr>
                    <td class="font-medium py-1 pr-3">No PO</td>
                    <td>: {{ $stok->inbound->purchaseOrder->no_po }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Tanggal PO</td>
                    <td>: {{ \Carbon\Carbon::parse($stok->inbound->purchaseOrder->created_at)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="font-medium py-1 pr-3">Catatan</td>
                    <td>: {{ $stok->inbound->purchaseOrder->catatan ?? '-' }}</td>
                </tr>
            </table>
            @else
            <p class="text-gray-500 italic">Tidak memiliki Purchase Order.</p>
            @endif

            @if($stok->inbound && $stok->inbound->purchaseOrder)


            @php
            $canSeeHarga = session('superadmin') || session('manager');
            @endphp


            @if($stok->inbound->purchaseOrder->items && $stok->inbound->purchaseOrder->items->count())
            <h4 class="font-semibold mt-3 mb-1">Rincian Item PO:</h4>
            <table class="w-full text-sm border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-2 py-1 text-left">Produk</th>
                        <th class="border px-2 py-1 text-right">Qty</th>
                        @if($canSeeHarga)
                        <th class="border px-2 py-1 text-right">Harga</th>
                        <th class="border px-2 py-1 text-right">Subtotal</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($stok->inbound?->stok ?? [] as $item)
                    @php
                    $subtotal = $item->total_harga_beli ?? 0;
                    $total += $subtotal;
                    @endphp
                    <tr>
                        <td class="border px-2 py-1">{{ $item->product->nama ?? '-' }}</td>
                        <td class="border px-2 py-1 text-right">{{ $item->berat_kg ?? $item->qty }}</td>
                        @if($canSeeHarga)
                        <td class="border px-2 py-1 text-right">Rp {{ number_format($item->ss_harga_beli) }}</td>
                        <td class="border px-2 py-1 text-right">Rp {{ number_format($subtotal) }}</td>
                        @endif
                    </tr>
                    @endforeach
                    @if($canSeeHarga)
                    <tr class="font-semibold bg-gray-100">
                        <td colspan="3" class="border px-2 py-1 text-right">Total</td>
                        <td class="border px-2 py-1 text-right">Rp {{ number_format($total) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            @endif


            @else
            <p class="text-sm text-gray-500">Stok ini tidak memiliki Purchase Order.</p>
            @endif

        </div>

        {{-- Bagian Transfer Stok --}}
        <div class="md:col-span-2 mt-6">
            <h2 class="text-lg font-semibold mb-2">Transfer Stok</h2>

            <x-alert-success />
            <x-alert-error />

            <form action="{{ route('stok.transfer', $stok->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Pilih Cabang Tujuan --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Transfer ke Cabang</label>
                        <select name="cabang_tujuan_id" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach($listCabang as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Kurir --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Kurir</label>
                        <select name="kurir" class="w-full border rounded px-3 py-2" onchange="toggleNoResiField(this.value)">
                            <option value="">Pilih</option>
                            <option value="1">Kurir Toko</option>
                            <option value="2">Gojek</option>
                            <option value="3">Grab</option>
                            <option value="4">Lalamove</option>
                            <option value="5">Paxel</option>
                            <option value="6">Maxim</option>
                        </select>
                    </div>

                    {{-- No Resi (optional) --}}
                    <div id="noResiField" class="md:col-span-1">
                        <label class="block text-sm font-medium mb-1">No Resi (Opsional)</label>
                        <input type="text" name="no_resi" class="w-full border rounded px-3 py-2" placeholder="Masukkan nomor resi">
                    </div>

                    {{-- Nama Kurir --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Kurir</label>
                        <input type="text" name="nama_kurir" class="w-full border rounded px-3 py-2" placeholder="Nama kurir pengantar">
                    </div>

                    {{-- Keterangan --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full border rounded px-3 py-2" placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    {{-- Status Transfer --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Status Transfer</label>
                        <select name="status_transfer" class="w-full border rounded px-3 py-2">
                            <!-- <option value="1">Dikirim</option> -->
                            <option value="2">Sampai</option>
                            <!-- <option value="3">Batal</option> -->
                        </select>
                    </div>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Simpan Transfer Stok
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
