<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class OzonPostingItemData extends Data
{
    public function __construct(
        public OzonProductData $productData,
        public int $quantity,
    )
    {}
}
