<?php

return [
    'base_url' => env('OZON_BASE_URL', 'https://api-seller.ozon.ru'),
    'client_id' => env('OZON_CLIENT_ID', null),
    'api_key' => env('OZON_API_KEY', null),
    'product_list_limit' => env('OZON_LIST_LIMIT', 1000)
];
