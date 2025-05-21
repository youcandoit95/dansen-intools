<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Online Retail
            [
                'nama' => 'Online Retail - Tokopedia Store',
                'sales_agent_id' => null,
                'no_tlp' => '',
                'domisili' => 1,
                'alamat_lengkap' => '',
            ],
            // Offline Retail
            [
                'nama' => 'Offline Retail - Nasional',
                'sales_agent_id' => null,
                'no_tlp' => '',
                'domisili' => 1,
                'alamat_lengkap' => '',
            ],
            // Offline Resto
            [
                'nama' => 'Offline Resto - Nasional',
                'sales_agent_id' => null,
                'no_tlp' => '',
                'domisili' => 1,
                'alamat_lengkap' => '',
            ],
        ];

        foreach ($data as $item) {
            Customer::create($item);
        }
    }
}
