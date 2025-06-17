<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inbound extends Model
{
    use SoftDeletes;

    protected $table = 'inbounds';

    protected $fillable = [
        'no_surat_jalan',
        'purchase_order_id',
        'supplier_id',
        'foto_surat_jalan_1',
        'foto_surat_jalan_2',
        'foto_surat_jalan_3',
        'submitted_at',
        'submitted_by',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Relasi
    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function submittedBy() {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy() {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function stok()
{
    return $this->hasMany(Stok::class, 'inbound_id');
}

}
