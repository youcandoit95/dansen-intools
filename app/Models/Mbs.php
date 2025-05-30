<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mbs extends Model
{
    use HasFactory;

    protected $table = 'mbs';

    protected $fillable = [
        'a_grade',
        'bms',
    ];

    // Relasi ke produk (jika dibutuhkan)
    public function products()
    {
        return $this->hasMany(Product::class, 'mbs_id');
    }
}
