<?php

namespace App\Services\Ozon;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function getPostingsFbo($offset = 0, $since = null, $to = null)
    {
        $params = [
            'filter' => [
                'since' => $since ?? now()->subYear()->format('Y-m-d') . 'T00:00:00Z',
                'to' => $to ?? now()->format('Y-m-d') . 'T' . now()->format('H:i:s') . 'Z',
            ],
            'limit' => 1000,
            'offset' => $offset,
            'with' => [
                'analytics_data' => true
            ]
        ];
        $response = $this->http->post($this->baseUrl . '/v2/posting/fbo/list', $params);

        if (!$response->successful()) {
            Log::warning('getPostingsFbo not successful', ['response' => $response->getBody()]);
            return false;
        }
        $results = $response->object();

        return $results;
    }

    public function getPostingsFbs($offset = 0, $since = null, $to = null)
    {
        $params = [
            'filter' => [
                'since' => $since ?? now()->subYear()->addMinute(),
                'to' => $to ?? now(),
            ],
            'limit' => 1000,
            'offset' => $offset
        ];
        $response = $this->http->post($this->baseUrl . '/v3/posting/fbs/list', $params);

        if (!$response->successful()) {
            Log::warning('getPostingsFbs not successful', ['response' => $response->getBody()]);
            return false;
        }
        $results = $response->object();

        return $results;
    }

    public function getAnalyticsData()
    {
        $params = [
            'date_from' => now()->subYear()->format('Y-m-d'),
            'date_to' => now()->format('Y-m-d'),
            'metrics' => [
                "hits_view",
                "hits_view_pdp",
                "hits_tocart"
            ],
            'dimension' => [
                'sku'
            ],
            'limit' => 1000
        ];

        $response = $this->http->post($this->baseUrl . '/v1/analytics/data', $params);

        if (!$response->successful()) {
            Log::warning('getAnalyticsData not successful', ['response' => $response->getBody()]);
            return false;
        }
        $results = $response->object();

        return $results->result->data;
    }
}
