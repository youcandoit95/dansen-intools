<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'product_id',
        'sales_agent_id',
        'harga_jual',
        'komisi_sales',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function salesAgent() { return $this->belongsTo(SalesAgent::class); }
}

class CustomerBlacklist extends Model
{
    use SoftDeletes;

    protected $fillable = ['customer_id', 'alasan'];

    public function customer() { return $this->belongsTo(Customer::class); }
}
