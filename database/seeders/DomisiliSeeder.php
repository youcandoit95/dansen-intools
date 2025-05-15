<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Domisili;

class DomisiliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $kotaList = [
                        'Ambon', 'Balikpapan', 'Banda Aceh', 'Bandar Lampung', 'Bandung', 'Bali',  'Banjarmasin',
                        'Batam', 'Bekasi', 'Bengkulu', 'Binjai', 'Bogor', 'Bukittinggi', 'Cilegon',
                        'Cimahi', 'Cirebon', 'Denpasar', 'Depok', 'Gorontalo', 'Jakarta Barat',
                        'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Utara', 'Jambi', 'Lombok',
                        'Jayapura', 'Kediri', 'Kendari', 'Langsa', 'Lhokseumawe', 'Lubuklinggau',
                        'Madiun', 'Makassar', 'Malang', 'Manado', 'Mataram', 'Medan', 'Riau',
                        'Mojokerto', 'Padang',
                        'Palangkaraya', 'Palembang', 'Palopo', 'Palu', 'Pangkal Pinang', 'Parepare',
                        'Pariaman', 'Pasuruan', 'Payakumbuh', 'Pekalongan', 'Pekanbaru',
                        'Pematang Siantar', 'Pontianak', 'Prabumulih', 'Probolinggo', 'Sabang',
                        'Salatiga', 'Samarinda', 'Sawahlunto', 'Semarang', 'Serang', 'Sibolga',
                        'Singkawang', 'Solok', 'Sorong', 'Subulussalam', 'Sukabumi', 'Sungai Penuh',
                        'Surabaya', 'Surakarta', 'Tangerang', 'Tangerang Selatan', 'Tanjung Balai',
                        'Tanjung Pinang', 'Tarakan', 'Tasikmalaya', 'Tebing Tinggi', 'Tegal',
                        'Ternate', 'Tidore Kepulauan', 'Tomohon', 'Tual', 'Yogyakarta'
                    ];



        foreach ($kotaList as $nama) {
            Domisili::create(['nama' => $nama]);
        }
    }
}
