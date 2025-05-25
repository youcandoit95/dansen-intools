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
        'mbs_id',
        'bagian_daging_id',
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

    public function mbs()
    {
        return $this->belongsTo(Mbs::class, 'mbs_id');
    }

    public function bagianDaging()
    {
        return $this->belongsTo(BagianDaging::class, 'bagian_daging_id');
    }

    public function getKategoriLabelAttribute()
    {
        return match ($this->kategori) {
            1 => 'Loaf/Kg',
            2 => 'Pack/Kg',
            3 => 'Pcs/Pack',
            default => 'Tidak diketahui'
        };
    }

    public function getBrandLabelAttribute()
    {
        return match ($this->brand) {
            1 => 'Tokusen Wagyu',
            2 => 'Santori',
            3 => 'Sher Wagyu',
            default => 'Tidak diketahui'
        };
    }

    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Aktif' : 'Tidak Aktif';
    }

    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function defaultSellPrice()
    {
        return $this->hasOne(DefaultSellPrice::class);
    }


}
