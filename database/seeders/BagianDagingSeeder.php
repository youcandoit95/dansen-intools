<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BagianDagingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Tenderloin',
            'Striploin',
            'Rib Eye',
            'Knuckle',
            'Rump',
            'Top Sirloin / Picanha Loaf',
            'Rump Eye',
            'Rump Roast',
            'Topside',
            'F/H Shin Shank',
            'Flank Steak',
            'Chuck',
            'Chuck Tender',
            'PE Brisket',
            'NE Brisket',
            'Oyster Blade',
            'Inside Skirt',
            'Outside Skirt',
            'Spare Rib Slice',
            'Bone Marrow u Shape',
            'Neck bone Slice',
            'Short rib 7 ribs',
            'Casazuki Ribs',
            'Oxtail',
            'Oxtail Slice',
            'Bolar Blade',
            'Tongue',
            'Silverside',
            'Chuckroll',
            'Tritip',
            'Chuck Flap',
            'Flap Meat',
            'Short rib Meat',
            'Back rib',
        ];

        foreach ($data as $nama) {
            DB::table('bagian_daging')->insert(['nama' => $nama, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}
