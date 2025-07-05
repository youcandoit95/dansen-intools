<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'inv_no',
        'sales_agents_id',
        'company_id',
        'customer_id',
        'invoice_transaction_date',
        'g_total_invoice_amount',
        'platform_id',
        'lunas_at',
        'checked_finance_at',
        'created_by',
        'cancel',
    ];

    protected $casts = [
        'invoice_transaction_date' => 'date',
        'g_total_invoice_amount' => 'integer',
        'lunas_at' => 'datetime',
        'checked_finance_at' => 'datetime',
        'cancel' => 'boolean',
    ];

    // ===== RELATIONSHIPS =====

    public function salesAgent()
    {
        return $this->belongsTo(SalesAgent::class, 'sales_agents_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'inv_id');
    }

    // ===== ACCESSORS =====

    public function getPlatformTextAttribute()
    {
        return match ($this->platform_id) {
            1 => 'Tokopedia',
            2 => 'TiktokShop',
            3 => 'Shopee',
            4 => 'Blibli',
            5 => 'Toco',
            default => 'Offline',
        };
    }

    public function getStatusTextAttribute()
    {
        if ($this->cancel) return 'Batal';
        if ($this->lunas_at) return 'Lunas';
        if ($this->checked_finance_at) return 'Checked';
        return 'Belum Diproses';
    }

    public static function generateInvoiceNumber(): string
    {
        $initial = strtoupper(session('cabang_initial', 'XXX')); // fallback jika tidak ada
        $timestamp = now()->format('Ymd/His');

        return 'INV/' . $initial .'/'. $timestamp;
    }
}
