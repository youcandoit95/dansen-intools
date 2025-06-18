@extends('layouts.popup')

@section('title', 'Cetak Label Produk')

@section('content')
    <div class="label">
        <div class="qrcode" id="qrcode"></div>
        <div class="nama-produk">{{ $nama }}</div>
        <!-- <div class="barcode-number">{{ $barcode }}</div> -->
    </div>

    <button onclick="window.print()">🖨️ Cetak</button>
    <button onclick="window.close()">Keluar</button>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $barcode }}",
            width: 100,
            height: 100
        });

        // auto-markan barcode printed
        fetch("{{ route('cetak.label.mark') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ barcode: "{{ $barcode }}" })
        });
    </script>
@endsection
