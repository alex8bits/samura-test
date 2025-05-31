<?php

namespace App\Console\Commands;

use App\Models\OzonProduct;
use App\Services\Ozon\OzonService;
use Illuminate\Console\Command;

class GetAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ozonService = new OzonService();

        $results = $ozonService->getAnalyticsData();
        foreach ($results as $result) {
            OzonProduct::whereSku($result->dimensions[0]->id)
                ->update([
                    'hits_view' => $result->metrics[0],
                    'hits_view_pdp' => $result->metrics[1],
                    'hits_tocart' => $result->metrics[2],
                ]);
        }
    }
}
