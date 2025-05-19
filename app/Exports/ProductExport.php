<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::select('barcode', 'nama', 'kategori', 'brand', 'status', 'deskripsi')->get()->map(function ($product) {
            return [
                $product->barcode,
                $product->nama,
                $product->kategori_label,
                $product->brand_label,
                $product->status_label,
                $product->deskripsi,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Barcode',
            'Nama',
            'Kategori',
            'Brand',
            'Status',
            'Deskripsi',
        ];
    }
}
