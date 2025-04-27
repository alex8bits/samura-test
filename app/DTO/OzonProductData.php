<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class OzonProductData extends Data
{
    public function __construct(
        public string $offer_id,
        public string $name,
        public int $price
    )
    {}
}
