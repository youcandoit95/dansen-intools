<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mbs;

class MbsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['a_grade' => 'A1', 'bms' => 0],
            ['a_grade' => 'A1', 'bms' => 1],
            ['a_grade' => 'A2', 'bms' => 2],
            ['a_grade' => 'A3', 'bms' => 3],
            ['a_grade' => 'A3', 'bms' => 4],
            ['a_grade' => 'A4', 'bms' => 5],
            ['a_grade' => 'A4', 'bms' => 6],
            ['a_grade' => 'A4', 'bms' => 7],
            ['a_grade' => 'A5', 'bms' => 8],
            ['a_grade' => 'A5', 'bms' => 9],
            ['a_grade' => 'A5', 'bms' => 10],
            ['a_grade' => 'A5', 'bms' => 11],
            ['a_grade' => 'A5', 'bms' => 12],
        ];

        foreach ($data as $item) {
            Mbs::create($item);
        }
    }
}
