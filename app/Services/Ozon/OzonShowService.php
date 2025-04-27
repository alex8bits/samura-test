<?php

namespace App\Services\Ozon;

use App\DTO\OzonProductData;

class OzonShowService implements OzonProductInterface
{
    protected $ozonService;

    public function __construct()
    {
        $this->ozonService = new OzonService();
    }

    public function getProducts()
    {
        $last_id = null;
        $offers = [];
        $products = [];
        while ($result = $this->ozonService->getProductList($last_id)) {
            if (count($result->items) == 0) {
                break;
            }
            $last_id = $result->last_id;
            foreach ($result->items as $item) {
                $offers[] = $item->offer_id;
            }

            $ozonProducts = $this->ozonService->getProductInfo($offers);
            if (!$ozonProducts) {
                return false;
            }
            if (count($ozonProducts->items) > 0) {
                foreach ($ozonProducts->items as $item) {
                    $product = OzonProductData::from($item);
                    $products[] = $product;
                }
            }
        }

        return $products;
    }
}
