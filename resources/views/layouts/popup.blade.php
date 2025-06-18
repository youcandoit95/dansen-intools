<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Popup')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: monospace;
            margin: 0;
            padding: 0;
            width: 48mm;
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
    @yield('head')
</head>
<body>
    @yield('content')
</body>
</html>
