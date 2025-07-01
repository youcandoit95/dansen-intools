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

        // Transfer stok
        'trfstok_cabang_asal_id',
        'trfstok_kurir',
        'trfstok_no_resi',
        'trfstok_nama_kurir',
        'trfstok_keterangan',
        'trfstok_status',
        'trfstok_status_tanggal',
    ];

    protected $casts = [
        'berat_kg' => 'decimal:3',
        'temp' => 'boolean',
        'destroy_at' => 'datetime',
        'destroy_approved_at' => 'datetime',
        'trfstok_status_tanggal' => 'datetime',
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
        return $this->belongsTo(Cabang::class, 'cabang_id'); // tujuan
    }

    public function trfCabangAsal()
    {
        return $this->belongsTo(Cabang::class, 'trfstok_cabang_asal_id');
    }

    public function destroyer()
    {
        return $this->belongsTo(User::class, 'destroy_by');
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

    // OPTIONAL: Label kurir dan status transfer
    public function getTrfstokKurirLabelAttribute()
    {
        return match ($this->trfstok_kurir) {
            1 => 'Kurir Toko',
            2 => 'Gojek',
            3 => 'Grab',
            4 => 'Lalamove',
            5 => 'Paxel',
            6 => 'Maxim',
            default => null,
        };
    }

    public function getTrfstokStatusLabelAttribute()
    {
        return match ($this->trfstok_status) {
            1 => 'Dikirim',
            2 => 'Sampai',
            3 => 'Batal',
            default => null,
        };
    }
}
