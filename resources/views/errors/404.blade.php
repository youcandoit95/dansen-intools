<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />


</head>

<body class="bg-gray-100">
    <!-- Main Content shifted right by sidebar width -->

        <div class="min-h-screen flex items-center justify-center bg-gray-100">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-gray-800">404</h1>
                <p class="text-xl mt-4">Halaman Tidak Ditemukan</p>
                <p class="mt-2 text-gray-600">Halaman yang Anda cari mungkin telah dipindahkan atau tidak tersedia.</p>
                <a href="{{ route('dashboard') }}" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>


    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
