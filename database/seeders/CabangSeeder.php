<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    public function run()
    {
        Cabang::create([
            'nama_cabang' => 'Cabang Tangsel',
            'alamat'      => 'Tangsel, Indonesia',
            'telepon'     => '081234567890',
            'nama_pic'    => 'Azhima',
            'status'      => true,
        ]);

        Cabang::create([
            'nama_cabang' => 'Cabang Bekasi',
            'alamat'      => 'Bekasi, Indonesia',
            'telepon'     => '081234567891',
            'nama_pic'    => 'Default PIC',
            'status'      => true,
        ]);

        Cabang::create([
            'nama_cabang' => 'Cabang Jakarta',
            'alamat'      => 'Jakarta, Indonesia',
            'telepon'     => '081234567892',
            'nama_pic'    => 'Default PIC',
            'status'      => true,
        ]);

        Cabang::create([
            'nama_cabang' => 'Cabang Depok',
            'alamat'      => 'Depok, Indonesia',
            'telepon'     => '081234567892',
            'nama_pic'    => 'Default PIC',
            'status'      => true,
        ]);
    }
}
