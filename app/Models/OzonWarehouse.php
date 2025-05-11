<?php

namespace App\Models;

use App\Enums\OzonWarehouseTypes;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $warehouse_id
 * @property $name
 * @property $type
 */
class OzonWarehouse extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'warehouse_id',
        'name',
        'type',
    ];

    protected $casts = [
        'type' => OzonWarehouseTypes::class,
    ];
}
