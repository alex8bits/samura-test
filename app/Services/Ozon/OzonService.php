<?php

namespace App\Services\Ozon;

use Illuminate\Support\Facades\Http;

class OzonService
{
    protected $baseUrl;
    protected $clientId;
    protected $apiKey;
    protected $http;

    public function __construct()
    {
        $this->baseUrl = config('ozon.base_url');
        $this->clientId = config('ozon.client_id');
        $this->apiKey = config('ozon.api_key');
        $this->http = Http::withHeaders([
            'Client-Id' => $this->clientId,
            'Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
    }

    public function getProductList($last_id = null)
    {
        $params = [
            'filter' => [
                'visibility' => 'ALL'
            ],
            'limit' => 1000
        ];
        if ($last_id) {
            $params['last_id'] = $last_id;
        }
        $response = $this->http->post($this->baseUrl . '/v3/product/list', $params);
        if (!$response->successful()) {
            return false;
        }
        $results = $response->object();

        if ($results->result->total == 0) {
            return false;
        }

        return $results->result;
    }

    public function getProductInfo($offers)
    {
        $response = $this->http->post($this->baseUrl . '/v3/product/info/list', [
            'offer_id' => $offers
        ]);
        if (!$response->successful()) {
            return false;
        }
        $results = $response->object();

        return $results;
    }
}
