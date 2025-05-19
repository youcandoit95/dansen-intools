@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">Tambah Produk</h2>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('products.form', ['product' => null])

        <div class="mt-4 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const barcodeInput = document.getElementById('barcode');
        const generateBtn = document.getElementById('generateBarcode');
        generateBtn.addEventListener('click', function () {
            barcodeInput.value = generateUPC();
        });
        function generateUPC() {
            let upcBase = "0";
            for (let i = 0; i < 10; i++) {
                upcBase += Math.floor(Math.random() * 10).toString();
            }
            const checkDigit = calculateUPCCheckDigit(upcBase);
            return upcBase + checkDigit;
        }
        function calculateUPCCheckDigit(upc) {
            let sumOdd = 0, sumEven = 0;
            for (let i = 0; i < upc.length; i++) {
                const digit = parseInt(upc[i]);
                if (i % 2 === 0) sumOdd += digit;
                else sumEven += digit;
            }
            const total = (sumOdd * 3) + sumEven;
            const mod = total % 10;
            return mod === 0 ? 0 : 10 - mod;
        }
    });
</script>
@endsection
