<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangInitialSeeder extends Seeder
{
    public function run(): void
    {
        $initials = [
            1 => 'AZH',
            2 => 'BKS',
            3 => 'JKT',
            4 => 'DPK',
        ];

        foreach ($initials as $id => $init) {
            DB::table('cabang')->where('id', $id)->update(['initial' => $init]);
        }
    }
}
