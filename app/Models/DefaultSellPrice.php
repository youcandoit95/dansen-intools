<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefaultSellPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'online_sell_price',
        'offline_sell_price',
        'reseller_sell_price',
        'resto_sell_price',
        'bottom_sell_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
