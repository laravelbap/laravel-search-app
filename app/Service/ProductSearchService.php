<?php

namespace App\Service;

use Algolia\AlgoliaSearch\SearchClient;
use App\Data\Param\ProductSearchData;
use App\Data\Result\SearchResult;

/**
 * Search Product Service
 */
class ProductSearchService
{
    protected $client;
    protected string $indexName = 'products';

    public function __construct()
    {
        $appId = config('scout.algolia.id');
        $apiKey = config('scout.algolia.secret');

        if (!$appId || !$apiKey) {
            throw new \RuntimeException('Missing ALGOLIA_APP_ID or ALGOLIA_SECRET in config/scout.php/.env');
        }

        $this->client = SearchClient::create($appId, $apiKey);
    }

    /**
     * Search with proper disjunctive facet counts (facets don't disappear).
     */
    public function search(ProductSearchData $data, int $page = 1, int $perPage = 12): SearchResult
    {
        $index = $this->client->initIndex($this->indexName);

        // Define facet behavior (Checkbox - OR)
        $disjunctiveFacets = [
            'connector_type_name',
            'manufacturer_name'
        ];
        $conjunctiveFacets = [
            'type', // single-choice
        ];

        // Build facetFilters (array-of-arrays) and numericFilters
        $facetFilters = [];
        $numericFilters = [];

        // Conjunctive example: product type
        if ($data->filterProductType) {
            $facetFilters[] = ['type:' . $this->escapeFacet($data->filterProductType)];
        }

        //  connector_type (OR group)
        if (!empty($data->filterConnectorType) && is_array($data->filterConnectorType)) {
            $group = [];
            foreach ($data->filterConnectorType as $val) {
                $group[] = 'connector_type_name:' . $this->escapeFacet($val);
            }
            if ($group) {
                $facetFilters[] = $group;
            }
        }

        //  manufacturer disjunctive
        if (!empty($data->filterManufacturer) && is_array($data->filterManufacturer)) {
            $group = [];
            foreach ($data->filterManufacturer as $val) {
                $group[] = 'manufacturer_name:' . $this->escapeFacet($val);
            }
            if ($group) {
                $facetFilters[] = $group;
            }
        }

        // Numeric range (price)
        if ($data->filterPrice && ($data->filterPrice->min !== null || $data->filterPrice->max !== null)) {
            if ($data->filterPrice->min !== null) {
                $numericFilters[] = "price >= {$data->filterPrice->min}";
            }
            if ($data->filterPrice->max !== null) {
                $numericFilters[] = "price <= {$data->filterPrice->max}";
            }
        }

        // power_output
        if ($data->filterPowerOutput && ($data->filterPowerOutput->min !== null || $data->filterPowerOutput->max !== null)) {
            if ($data->filterPowerOutput->min !== null) {
                $numericFilters[] = "power_output >= {$data->filterPowerOutput->min}";
            }
            if ($data->filterPowerOutput->max !== null) {
                $numericFilters[] = "power_output <= {$data->filterPowerOutput->max}";
            }
        }

        // capacity
        if ($data->filterCapacity && ($data->filterCapacity->min !== null || $data->filterCapacity->max !== null)) {
            if ($data->filterCapacity->min !== null) {
                $numericFilters[] = "capacity >= {$data->filterCapacity->min}";
            }
            if ($data->filterCapacity->max !== null) {
                $numericFilters[] = "capacity <= {$data->filterCapacity->max}";
            }
        }

        $query = $data->searchTerm ?? '';

        // Search
        $mainParams = [
            'page' => max(0, $page - 1),
            'hitsPerPage' => $perPage,
            'facets' => array_merge($disjunctiveFacets, $conjunctiveFacets),
            'maxValuesPerFacet' => 100,
        ];
        if ($facetFilters) $mainParams['facetFilters'] = $facetFilters;
        if ($numericFilters) $mainParams['numericFilters'] = $numericFilters;

        $mainRes = $index->search($query, $mainParams);

        // Disjunctive facet recomputation (remove that facet’s own filter group)
        $facetCounts = $mainRes['facets'] ?? [];
        foreach ($disjunctiveFacets as $attr) {
            $filtersExcludingThis = $this->removeFacetGroup($facetFilters, $attr);

            $params = [
                'page' => 0,
                'hitsPerPage' => 0,
                'facets' => [$attr],
                'maxValuesPerFacet' => 100,
            ];
            if ($filtersExcludingThis) $params['facetFilters'] = $filtersExcludingThis;
            if ($numericFilters) $params['numericFilters'] = $numericFilters;

            $res = $index->search($query, $params);
            $facetCounts[$attr] = $res['facets'][$attr] ?? [];
        }

        // Map data to your SearchResult(Data) shape
        $filtersPayload = [
            'facetFilters' => $facetFilters,
            'selected' => [
                'type' => $data->filterProductType ?? null,
                'connector_type_name' => $data->filterConnectorType ?? [],
                'manufacturer_name' => $data->filterManufacturer ?? [],
            ],
        ];

        $numericPayload = $numericFilters;

        $rawPayload = [
            'nbPages' => $mainRes['nbPages'] ?? 0,
            'processingTimeMS' => $mainRes['processingTimeMS'] ?? null,
            'facets' => $facetCounts,
            'disjunctiveFacets' => $disjunctiveFacets,
            'conjunctiveFacets' => $conjunctiveFacets,
        ];

        return new SearchResult(
            query: $query,
            filters: $filtersPayload,
            numeric: $numericPayload,
            page: ($mainRes['page'] ?? 0) + 1,
            perPage: $perPage,
            total: $mainRes['nbHits'] ?? 0,
            hits: $mainRes['hits'] ?? [],
            raw: $rawPayload,
        );
    }

    /**
     * Remove groups from facetFilters that correspond to a given attribute.
     */
    protected function removeFacetGroup(?array $facetFilters, string $attr): array
    {
        if (empty($facetFilters)) return [];

        $new = [];
        foreach ($facetFilters as $group) {
            $g = is_array($group) ? $group : [$group];
            $skip = false;
            foreach ($g as $entry) {
                if (strpos($entry, $attr . ':') === 0) {
                    $skip = true;
                    break;
                }
            }
            if (!$skip) $new[] = $g;
        }
        return $new;
    }

    protected function escapeFacet(string $value): string
    {
        return str_replace(':', '\:', $value);
    }
}
