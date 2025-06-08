<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $posting_number
 * @property $order_id
 * @property $order_number
 * @property $warehouse_id
 * @property $price
 */
class OzonPosting extends Model
{
    protected $fillable = [
        'posting_number',
        'order_id',
        'order_number',
        'warehouse_id',
        'price',
        'created_at'
    ];

    public function items()
    {
        return $this->hasMany(OzonPostingItem::class, 'posting_id');
    }

    public function total()
    {
        return $this->getAttribute('price') ?? $this->items()->sum('price');
    }

    public function warehouse()
    {
        return $this->belongsTo(OzonWarehouse::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter): Builder
    {
        return $filter->apply($builder);
    }
}
