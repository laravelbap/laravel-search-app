<?php

namespace App\Console\Commands;

use App\Data\Param\ProductSearchData;
use App\Enum\ProductType;
use App\Service\ProductSearchService;
use Illuminate\Console\Command;

class SearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:search-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search command for testing';

    /**
     * Execute the console command.
     */
    public function handle(ProductSearchService $productSearchService)
    {
        $productSearchData = ProductSearchData::from([
          //  'searchTerm' => 'Amp',
            'filterProductType' => ProductType::CONNECTOR->value,
            /*'filterPrice' => [
                'min' => 50,
                'max' => 1000,
            ]*/
        ]);

        $result = $productSearchService->search($productSearchData);


        dd($result);
    }
}
