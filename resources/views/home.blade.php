@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Alert -->
<div class="bg-blue-100 text-blue-800 text-sm px-4 py-3 rounded mb-4 border border-blue-200">
    <strong>Informasi Pengembangan Sistem:</strong><br>
    (Update: 28-04-2025 17:13) Saat ini sistem masih dalam pengembangan
</div>

<!-- Header -->
<h1 class="text-xl font-semibold mb-4">Informasi Performa Toko</h1>

<!-- Tabs -->
<div class="mb-4 border-b border-gray-200">
    <ul class="flex space-x-4 text-sm font-medium">
        <li><a href="#" class="border-b-2 border-blue-600 text-blue-600 py-2 px-3">Hari Ini</a></li>
        <li><a href="#" class="py-2 px-3 text-gray-500 hover:text-blue-600">Minggu Ini</a></li>
        <li><a href="#" class="py-2 px-3 text-gray-500 hover:text-blue-600">Bulan Ini</a></li>
        <li><a href="#" class="py-2 px-3 text-gray-500 hover:text-blue-600">Tahunan</a></li>
    </ul>
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded border">
        <div class="font-medium">Penjualan</div>
        <div class="text-lg font-semibold">Rp 0</div>
    </div>
    <div class="bg-white p-4 rounded border flex items-start justify-between" x-data="{ show: false }">
        <div>
            <div class="font-medium">Potensi Keuntungan</div>
            <div class="text-lg font-semibold">
                <span x-show="show">Rp 0</span>
                <span x-show="!show">Rp xxx</span>
            </div>
        </div>
        <button @click="show = !show"
            class="text-sm px-3 py-1 border rounded text-gray-600 hover:bg-gray-100 ml-4">
            <span x-text="show ? 'Sembunyikan' : 'Tampilkan'"></span>
        </button>
    </div>

    <div class="bg-white p-4 rounded border">
        <div class="font-medium">Produk Terjual</div>
        <div class="text-lg font-semibold">0</div>
    </div>
    <div class="bg-white p-4 rounded border">
        <div class="font-medium">Pelanggan Terbaik</div>
        <div class="text-gray-500">-<br>Rp 0 (0 qty)</div>
    </div>
    <div class="bg-white p-4 rounded border">
        <div class="font-medium">Produk Terlaku</div>
        <div class="text-gray-500">-<br>Rp 0 (0 qty)</div>
    </div>
    <div class="bg-white p-4 rounded border">
        <div class="font-medium">Transaksi Tertinggi</div>
        <div class="text-lg font-semibold">Rp 0</div>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-white p-6 rounded shadow border">
    <div class="mb-2 font-semibold">Penjualan (7 Hari Terakhir)</div>
    <div class="text-sm text-gray-500 mb-4">Periode tanggal :</div>
    <canvas id="salesChart" height="100"></canvas>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Selasa 22 Apr', 'Rabu 23 Apr', 'Kamis 24 Apr', 'Jumat 25 Apr', 'Sabtu 26 Apr', 'Minggu 27 Apr', 'Senin 28 Apr'],
                datasets: [{
                        label: 'Penjualan Harian (Rp)',
                        data: [50000, 125000, 100000, 15000, 220000, 200000, 125500],
                        backgroundColor: 'rgba(0, 172, 193, 0.4)',
                        borderColor: 'rgba(0, 172, 193, 1)',
                        borderWidth: 1
                    },
                    {
                        type: 'line',
                        label: 'Tren Penjualan',
                        data: [50000, 125000, 100000, 15000, 220000, 200000, 125500],
                        borderColor: '#ef4444',
                        backgroundColor: '#ef4444',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const dataset = context.dataset;
                                const current = dataset.data[index];

                                if (dataset.label === 'Tren Penjualan' && index > 0) {
                                    const prev = dataset.data[index - 1];
                                    const selisih = current - prev;
                                    const prefix = selisih >= 0 ? '+' : '-';
                                    return `${dataset.label}: ${prefix}Rp ${Math.abs(selisih).toLocaleString()}`;
                                }

                                return `${dataset.label}: Rp ${current.toLocaleString()}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }

        });
    });
</script>
@endsection
