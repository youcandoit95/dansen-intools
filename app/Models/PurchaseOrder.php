<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'no_po',
        'supplier_id',
        'tanggal',
        'tanggal_permintaan_dikirim',
        'catatan',
        'ajukan_at', 'ajukan_by',
        'sendemail_at', 'sendemail_by',
        'cabang_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function ajukanBy()
    {
        return $this->belongsTo(User::class, 'ajukan_by');
    }

    public function sendEmailBy()
    {
        return $this->belongsTo(User::class, 'sendemail_by');
    }

}

