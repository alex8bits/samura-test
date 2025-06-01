<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $posting_id
 * @property $price
 * @property $offer_id
 * @property $name
 * @property $sku
 * @property $quantity
 */
class OzonPostingItem extends Model
{
    protected $fillable = [
        'posting_id',
        'product_id',
        'price',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(OzonProduct::class);
    }
}
