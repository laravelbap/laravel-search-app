<?php

namespace App\Console\Commands;

use Algolia\AlgoliaSearch\SearchClient;
use Illuminate\Console\Command;

class AlgoliaSetupProductsIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:algolia:setup-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Algolia products index';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appId = config('scout.algolia.id');
        $apiKey = config('scout.algolia.secret');

        if (!$appId || !$apiKey) {
            $this->error('Missing ALGOLIA_APP_ID / ALGOLIA_SECRET (check .env and config/scout.php).');
            return self::FAILURE;
        }

        $client = SearchClient::create($appId, $apiKey);
        $index = $client->initIndex('products');

        // Core index settings – tuned for SolarPanel/Battery/Connector aggregator
        $settings = [
            //  Search by name, manufacturer or description (full-text search)
            'searchableAttributes' => [
                'name',
                'manufacturer_name',
                'unordered(description)',
            ],

            // Filter by category(product type), manufacturer and price range
            'attributesForFaceting' => [
                'searchable(type)',
                'searchable(manufacturer_name)',
                'searchable(connector_type_name)',
            ],

            'renderingContent' => [
                'facetOrdering' => [
                    'facets' => [
                        'order' => ['type', 'manufacturer_name', 'connector_type_name'],
                    ],
                ],
            ],

            // What to return + highlighting
            'attributesToRetrieve' => [
                'object_id',
                'type',
                'model_id',
                'name',
                'description',
                'price',
                'created_at',
                'manufacturer_name',
                'manufacturer_id',
                'connector_type_name',
                'connector_type_id',
                'capacity',
                'power_output',

            ],
            'attributesToHighlight' => ['name', 'description','manufacturer_name'],

            // Ranking (freshness first; adjust as needed)
            'customRanking' => [
                'desc(created_at)',
            ],
            'ranking' => ['typo', 'geo', 'words', 'filters', 'proximity', 'attribute', 'exact', 'custom'],

            // Language / typos (Polish + English)
            'ignorePlurals' => ['pl', 'en'],
            'removeStopWords' => ['pl', 'en'],
            'queryLanguages' => ['pl', 'en'],

            // Typo tolerance defaults
            'minWordSizefor1Typo' => 4,
            'minWordSizefor2Typos' => 8,
            'maxValuesPerFacet' => 100,
        ];

        $this->info('Setting up products index...');
        $index->setSettings($settings);
        $this->info('Products index setup complete.');

        return self::SUCCESS;
    }
}
