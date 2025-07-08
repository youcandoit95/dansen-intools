<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellPriceSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'online', 'offline', 'reseller', 'resto', 'bottom',
        'created_by', 'deleted_by',
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public static function getActive()
    {
        return self::whereNull('deleted_at')->latest('created_at')->first();
    }
}
