<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceAddon extends Model
{
    use HasFactory;

    protected $table = 'invoice_addon';

    protected $fillable = [
        'inv_id',
        'nama',
        'qty',
        'harga',
        'total',
        'catatan',
        'created_by',
    ];

    // Relasi ke invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'inv_id');
    }

    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
