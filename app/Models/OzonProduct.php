<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $product_id
 * @property $offer_id
 * @property $sku
 * @property $name
 * @property $price
 * @property $hits_view
 * @property $hits_view_pdp
 * @property $hits_tocart
 */
class OzonProduct extends Model
{
    protected $fillable = [
        'product_id',
        'offer_id',
        'sku',
        'name',
        'price',
        'hits_view',
        'hits_view_pdp',
        'hits_tocart',
    ];

    public function getHitsView()
    {
        return is_null($this->hits_view) ? "Нет данных" : $this->hits_view;
    }

    public function getHitsViewPdp()
    {
        return is_null($this->hits_view_pdp) ? "Нет данных" : $this->hits_view_pdp;
    }

    public function getHitsViewToCart()
    {
        return is_null($this->hits_tocart) ? "Нет данных" : $this->hits_tocart;
    }
}
