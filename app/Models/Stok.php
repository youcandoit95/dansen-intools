<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';

    protected $fillable = [
        'product_id',
        'kategori',             // 1=loaf/kg, 2=cut/kg, 3=pcs/pack, 99=waste
        'berat_kg',
        'barcode_stok',
        'destroy_at',
        'destroy_type',         // 1=hilang, 2=rusak
        'destroy_by',
        'destroy_reason',
        'destroy_foto',
        'destroy_approved_at',
        'destroy_approved_by',
        'created_by',
    ];

    // === RELASI ===
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inbound()
    {
        return $this->belongsTo(Inbound::class);
    }

    public function destroyBy()
    {
        return $this->belongsTo(User::class, 'destroy_by');
    }

    public function destroyApprovedBy()
    {
        return $this->belongsTo(User::class, 'destroy_approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // === ACCESSOR TAMBAHAN (opsional) ===
    public function getKategoriLabelAttribute()
    {
        return match ($this->kategori) {
            1 => 'loaf/kg',
            2 => 'cut/kg',
            3 => 'pcs/pack',
            99 => 'waste',
            default => 'unknown',
        };
    }

    public function getDestroyTypeLabelAttribute()
    {
        return match ($this->destroy_type) {
            1 => 'Hilang',
            2 => 'Rusak',
            default => null,
        };
    }
}
