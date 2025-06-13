<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';

    protected $fillable = [
        'product_id',
        'kategori',
        'berat_kg',
        'destroy_at',
        'destroy_by',
        'destroy_reason',
        'destroy_foto',
        'destroy_approved_at',
        'destroy_approved_by',
        'created_by',
    ];

    // Relasi
    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function destroyBy() {
        return $this->belongsTo(User::class, 'destroy_by');
    }

    public function destroyApprovedBy() {
        return $this->belongsTo(User::class, 'destroy_approved_by');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
