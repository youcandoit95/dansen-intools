<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';

    protected $fillable = [
        'product_id',
        'cabang_id',
        'inbound_id',
        'kategori',
        'berat_kg',
        'ss_harga_beli',
        'total_harga_beli',
        'barcode_stok',
        'temp',
        'created_by',
        'destroy_at',
        'destroy_type',
        'destroy_by',
        'destroy_reason',
        'destroy_foto',
        'destroy_approved_at',
        'destroy_approved_by',
    ];

    protected $casts = [
        'berat_kg' => 'decimal:3',
        'temp' => 'boolean',
        'destroy_at' => 'datetime',
        'destroy_approved_at' => 'datetime',
    ];

    protected $appends = ['kategori_label', 'destroy_type_label'];

    public $timestamps = true;

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

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    // === ACCESSOR ===
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
