<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = ['mm', 'H', 'global', 'mario', 'mojo'];

        foreach ($suppliers as $name) {
            Supplier::create(['name' => $name]);
        }
    }
}
