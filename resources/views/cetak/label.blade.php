<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Cetak Label Produk</title>
    <style>
        body {
            font-family: monospace;
            width: 48mm;
            margin: 0;
            padding: 0 2mm;
            text-align: center;
        }

        .label {
            padding: 10px 0;
            border-bottom: 1px dashed #000;
            page-break-after: always;
        }

        .label .qrcode {
            margin: 0 auto 10px;
            width: 100px;
            height: 100px;
        }

        .nama-produk {
            font-weight: bold;
            font-size: 16px;
            margin: 4px 0;
        }

        .barcode-number {
            font-size: 12px;
            color: #333;
            margin-top: 2px;
        }

        @media print {
            @page {
                size: 48mm 210mm;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
                width: 48mm;
            }

            button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="label">
        <div class="qrcode" id="qrcode"></div>
        <div class="nama-produk">{{ $nama }}</div>
        <div class="barcode-number">{{ $barcode }}</div>
    </div>

    <button onclick="window.print()">🖨️ Cetak Label</button>
    <button onclick="window.close()">Keluar</button>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $barcode }}",
            width: 100,
            height: 100
        });
    </script>
</body>
</html>
