<?php

namespace App\Console\Commands;

use App\Enums\OzonWarehouseTypes;
use App\Models\OzonPosting;
use App\Models\OzonPostingItem;
use App\Models\OzonProduct;
use App\Models\OzonWarehouse;
use App\Services\Ozon\OzonService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportPostings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-postings';

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
        $since = Carbon::now()->subYear();

        do {
            $offset = 0;
            $shouldRestart = false;

            do {
                usleep(23000);
                $postings = $ozonService->getPostingsFbo($offset, $since);
                foreach ($postings->result as $result) {
                    DB::beginTransaction();
                    $warehouse = OzonWarehouse::firstOrCreate([
                        'warehouse_id' => $result->analytics_data->warehouse_id
                    ], [
                        'name' => $result->analytics_data->warehouse_name,
                        'type' => OzonWarehouseTypes::FBO
                    ]);
                    $posting = OzonPosting::firstOrCreate([
                        'posting_number' => $result->posting_number,
                    ], [
                        'order_id' => $result->order_id,
                        'order_number' => $result->order_number,
                        'warehouse_id' => $warehouse->id,
                        'created_at' => $result->created_at,
                    ]);

                    if ($posting->wasRecentlyCreated) {
                        foreach ($result->products as $post_products) {
                            $product = OzonProduct::whereSku($post_products->sku)->first();
                            if ($product) {
                                OzonPostingItem::create([
                                    'posting_id' => $posting->id,
                                    'product_id' => $product->id,
                                    'quantity' => $post_products->quantity,
                                    'price' => $post_products->price,
                                ]);
                            }
                        }
                    }

                    DB::commit();
                    $latest = Carbon::parse($result->created_at);
                }
                $offset += 1000;

                if ($offset >= 20000 && count($postings->result) > 0) {
                    $since = $latest;
                    $shouldRestart = true;
                    break;
                }

            } while ($postings->result && count($postings->result));

        } while ($shouldRestart);


        $offset = 0;
        do {
            $postings = $ozonService->getPostingsFbs($offset);
            foreach ($postings->result->postings as $result) {
                DB::beginTransaction();
                $warehouse = OzonWarehouse::firstOrCreate([
                    'warehouse_id' => $result->delivery_method->warehouse_id
                ], [
                    'name' => $result->delivery_method->warehouse,
                    'type' => OzonWarehouseTypes::FBS
                ]);
                $posting = OzonPosting::updateOrCreate([
                    'posting_number' => $result->posting_number,
                ], [
                    'order_id' => $result->order_id,
                    'order_number' => $result->order_number,
                    'warehouse_id' => $warehouse->id,
                    'created_at' => $result->in_process_at,
                ]);
                if ($posting->wasRecentlyCreated) {
                    foreach ($result->products as $post_products) {
                        $product = OzonProduct::whereSku($post_products->sku)->first();
                        if ($product) {
                            OzonPostingItem::create([
                                'posting_id' => $posting->id,
                                'product_id' => $product->id,
                                'quantity' => $post_products->quantity,
                                'price' => $post_products->price,
                            ]);
                        }
                    }
                }
                DB::commit();
            }
            $offset += 1000;
        } while ($postings->result->has_next);
    }
}
