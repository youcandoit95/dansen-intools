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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.min.css" rel="stylesheet">


</head>

<body class="bg-gray-100">

    <!-- Sidebar Fixed -->
    <aside class="fixed top-0 left-0 h-screen w-64 bg-white border-r p-4 overflow-y-auto">
        <h2 class="text-lg font-semibold mb-1">Dansen System</h2>
        <p class="text-sm text-red-600 mb-4">
            {{ session('username') ?? '-' }} | {{ session('cabang_name') ?? '-' }}
        </p>
        <nav class="space-y-2">
            <!-- Beranda -->
            <a href="/dashboard"
                class="flex items-center gap-2 p-2 rounded
                    {{ ($activeMenu ?? '') === 'dashboard'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-house-door"></i> Beranda
            </a>


            <div class="text-sm text-gray-500 mb-1">Master Data</div>
            <!-- Cabang -->
            <a href="/cabang"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'cabang'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-building"></i> Cabang
            </a>

            <!-- Sales Agent -->
            <a href="/sales-agent"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'sales-agent'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-building"></i> Sales Agent
            </a>

            <!-- Supplier -->
            <a href="/suppliers"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'suppliers'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-building"></i> Suppliers
            </a>

            <!-- Product -->
            <a href="/products"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'products'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-building"></i> Product
            </a>

            <!-- Product Price -->
            <a href="/product-prices"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'product-price'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-building"></i> Product Price
            </a>

            <!-- default-sell-price -->
            <a href="/default-sell-price"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'product-price'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-building"></i> Default Sell Price
            </a>

            <!-- Customer -->
            <a href="/customers"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'customers'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-building"></i> Customer
            </a>

            <!-- Customer Price -->
            <a href="/customer-prices"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'customer-prices'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-tag"></i> Customer Sell Price
            </a>

            <!-- User -->
            <a href="/users"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'users'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-tag"></i> User
            </a>

            <div class="text-sm text-gray-500 mb-1">Transaksi</div>
            <!-- Purchase Order -->
            <a href="{{ route('purchase-order.index') }}"
                class="flex items-center gap-2 p-2 rounded ml-4
                    {{ ($activeMenu ?? '') === 'purchase-order'
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-file-earmark-text"></i> Purchase Order Re-Stock
            </a>

            <!-- Inbound -->
            <a href="{{ route('inbound.index') }}"
                class="flex items-center gap-2 p-2 rounded ml-4
          {{ ($activeMenu ?? '') === 'inbound'
              ? 'bg-blue-600 text-white'
              : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-truck"></i> Inbound / Stok Masuk
            </a>


            <a href="/logout" class="flex items-center gap-2 text-red-600 hover:bg-red-100 p-2 rounded mt-6">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content shifted right by sidebar width -->
    <main class="flex-1 p-6 ml-64">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>

</html>
