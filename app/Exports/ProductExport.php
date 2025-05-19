<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Product::all();
    // }
    public function collection()
    {
        return Product::select('barcode', 'nama', 'brand', 'kategori', 'status')->get();
    }

}
