<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = [
        'inv_id',
        'default_sell_price_id',
        'ss_online_sell_price',
        'ss_offline_sell_price',
        'ss_reseller_sell_price',
        'ss_resto_sell_price',
        'ss_bottom_sell_price',
        'product_id',
        'stok_id',
        'purchase_price',
        'customer_price_id',
        'sell_price',
        'ss_komisi_sales',
        'profit_gross',
        'qty',
        'total_purchase_price',
        'total_sell_price',
        'total_komisi_sales',
        'total_profit_gross',
        'note',
        'created_by',
    ];

    public $timestamps = false;

    protected $casts = [
        'ss_online_sell_price'    => 'integer',
        'ss_offline_sell_price'   => 'integer',
        'ss_reseller_sell_price'  => 'integer',
        'ss_resto_sell_price'     => 'integer',
        'ss_bottom_sell_price'    => 'integer',
        'purchase_price'          => 'integer',
        'sell_price'              => 'integer',
        'ss_komisi_sales'         => 'integer',
        'profit_gross'            => 'integer',
        'qty'                     => 'integer',
        'total_purchase_price'    => 'integer',
        'total_sell_price'        => 'integer',
        'total_komisi_sales'      => 'integer',
        'total_profit_gross'      => 'integer',
    ];

    // RELATIONSHIPS
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'inv_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stok()
    {
        return $this->belongsTo(Stok::class);
    }

    public function defaultSellPrice()
    {
        return $this->belongsTo(ProductPrice::class, 'default_sell_price_id');
    }

    public function customerPrice()
    {
        return $this->belongsTo(CustomerPrice::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
