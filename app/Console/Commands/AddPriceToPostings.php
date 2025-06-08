<?php

namespace App\Console\Commands;

use App\Models\OzonPosting;
use Illuminate\Console\Command;

class AddPriceToPostings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-price-to-postings';

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
        OzonPosting::whereNull('price')->chunkById(100, function ($ozonPostings) {
            /** @var OzonPosting $ozonPosting */
            foreach ($ozonPostings as $ozonPosting) {
                $total = 0;
                $items = $ozonPosting->items;
                foreach ($items as $item) {
                    $total += $item->price * $item->quantity;
                }
                $ozonPosting->update(['price' => $total]);
            }
        });
    }
}
