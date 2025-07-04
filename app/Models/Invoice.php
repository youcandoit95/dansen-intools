<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'inv_no', 'sales_agents_id', 'company_id', 'customer_id', 'invoice_transaction_date',
        'g_total_invoice_amount', 'platform_id', 'lunas_at', 'checked_finance_at', 'cancel'
    ];

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
}
