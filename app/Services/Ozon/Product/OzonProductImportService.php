<?php

namespace App\Services\Ozon\Product;

use App\Models\OzonProduct;
use App\Services\Ozon\OzonService;

class OzonProductImportService implements OzonProductInterface
{
    protected $ozonService;

    public function __construct()
    {
        $this->ozonService = new OzonService();
    }

    public function getProducts()
    {
        $last_id = null;
        while ($result = $this->ozonService->getProductList($last_id)) {
            $last_id = $result->last_id;
            $offers = [];
            foreach ($result->items as $item) {
                $offers[] = $item->offer_id;
            }

            $ozonProducts = $this->ozonService->getProductInfo($offers);
            if (!$ozonProducts) {
                return false;
            }
            if (count($ozonProducts->items) > 0) {
                $this->updateProducts($ozonProducts->items);
            }
        }
    }

    protected function updateProducts($items)
    {
        foreach ($items as $item) {
            OzonProduct::updateOrCreate([
                'product_id' => $item->id,
            ], [
                'offer_id' => $item->offer_id,
                'name' => $item->name,
                'price' => $item->marketing_price,
                'sku' => $item->sources[0]->sku ?? null,
            ]);
        }
    }
}
