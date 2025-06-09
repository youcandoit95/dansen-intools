<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $fillable = [
        'purchase_order_id', 'product_id', 'tipe', 'berat', 'qty',
        'qr_code', 'catatan',
        'destroy', 'catatan_destroy', 'destroyed_at', 'destroyed_by'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function destroyer()
    {
        return $this->belongsTo(User::class, 'destroyed_by');
    }
}
