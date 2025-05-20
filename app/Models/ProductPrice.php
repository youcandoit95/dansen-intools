<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redirect;

class ProductPrice extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'supplier_id', 'harga'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }


    public static function abortIfDuplicate($productId, $supplierId, $exceptId = null)
    {
        $query = self::where('product_id', $productId)
            ->where('supplier_id', $supplierId);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        if ($query->exists()) {
            Redirect::back()
                ->withInput()
                ->with('error', 'Kombinasi produk dan supplier sudah terdaftar.')
                ->throwResponse();
        }
    }
}
