<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class OzonProductData extends Data
{
    public function __construct(
        public int $product_id,
        public string $offer_id,
        public int $sku,
        public string $name,
        public int $price,
        public ?int $hits_view,
        public ?int $hits_view_pdp,
        public ?int $hits_tocart,
    )
    {}
}
