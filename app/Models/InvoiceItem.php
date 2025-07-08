<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = [
        'inv_id',
        'product_id',
        'stok_id',
        'purchase_price',
        'sell_price',
        'qty_outbound',
        'waste_kg',
        'waste_amount',
        'total_purchase_price',
        'total_sell_price',
        'profit_gross',
        'total_profit_gross',
        'ss_online_sell_price',
        'ss_offline_sell_price',
        'ss_reseller_sell_price',
        'ss_resto_sell_price',
        'ss_bottom_sell_price',
        'ss_komisi_sales',
        'total_komisi_sales',
        'final_profit_gross_after_waste',
        'note',
        'created_by',
    ];

    public $timestamps = false;

    protected $casts = [
        'purchase_price'          => 'integer',
        'sell_price'              => 'integer',
        'qty_outbound'            => 'decimal:3',
        'waste_kg'                => 'decimal:3',
        'waste_amount'            => 'integer',
        'total_purchase_price'    => 'integer',
        'total_sell_price'        => 'integer',
        'profit_gross'            => 'integer',
        'total_profit_gross'      => 'integer',
        'ss_online_sell_price'    => 'integer',
        'ss_offline_sell_price'   => 'integer',
        'ss_reseller_sell_price'  => 'integer',
        'ss_resto_sell_price'     => 'integer',
        'ss_bottom_sell_price'    => 'integer',
        'ss_komisi_sales'         => 'integer',
        'total_komisi_sales'      => 'integer',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
