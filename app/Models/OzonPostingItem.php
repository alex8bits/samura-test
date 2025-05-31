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
        'price',
        'offer_id',
        'name',
        'sku',
        'quantity',
    ];

    public function getProduct(): ?OzonProduct
    {
        return OzonProduct::whereSku($this->sku)->first();
    }
}
