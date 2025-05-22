<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BagianDaging extends Model
{
    use HasFactory;

    protected $table = 'bagian_daging';

    protected $fillable = [
        'nama',
    ];

    // Relasi ke produk (jika dibutuhkan)
    public function products()
    {
        return $this->hasMany(Product::class, 'bagian_daging_id');
    }
}
