<?php

namespace App\Console\Commands;

use App\Services\Ozon\Product\OzonProductImportService;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-products';

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
        $ozonProductService = new OzonProductImportService();
        $ozonProductService->getProducts();
    }
}
