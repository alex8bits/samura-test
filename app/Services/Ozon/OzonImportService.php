<?php

namespace App\Services\Ozon;

use App\Models\Product;

class OzonImportService implements OzonProductInterface
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
            Product::updateOrCreate([
                'article' => $item->offer_id,
            ], [
                'name' => $item->name,
                'price' => $item->price,
            ]);
        }
    }
}
