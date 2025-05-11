<?php

namespace App\DTO;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class OzonWarehouseData extends Data
{
    public function __construct(
        #[MapInputName('warehouse_id')]
        public int $id,
        #[MapInputName('warehouse_name')]
        public string $name,
        public string $type,
    )
    {}
}
