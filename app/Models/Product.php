<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductImage;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'barcode',
        'kategori',
        'brand',
        'ras',
        'nama',
        'deskripsi',
        'status'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getKategoriLabelAttribute()
    {
        return match ($this->kategori) {
            1 => 'Loaf/Kg',
            2 => 'Cut/Kg',
            3 => 'Pcs/Pack',
            default => 'Tidak diketahui'
        };
    }

    public function getBrandLabelAttribute()
    {
        return match ($this->brand) {
            1 => 'Tokusen',
            2 => 'Sher Wagyu',
            3 => 'Angus Pure/G',
            default => 'Tidak diketahui'
        };
    }

    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Aktif' : 'Tidak Aktif';
    }
}
