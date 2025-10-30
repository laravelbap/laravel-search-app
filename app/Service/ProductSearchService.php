<?php

namespace App\Service;

use App\Data\Param\ProductSearchData;
use App\Data\Param\RangeData;
use App\Data\Result\SearchResult;
use App\Search\Products;
use Illuminate\Support\Arr;

class ProductSearchService
{


    public function search(ProductSearchData $data, int $page = 1, int $perPage = 10): SearchResult
    {

        $filters = [];

        // Product Type (Solar Panel, Battery, Connector)
        if ($data->filterProductType) {
            $filters[] = 'type:' . $this->escapeFacet($data->filterProductType);
        }
        // Manufacturer
        if ($data->filterManufacturer) {
            $filters[] = 'manufacturer_name:' . $this->escapeFacet($data->filterManufacturer);
        }
        // Connector Type
        if ($data->filterConnectorType) {
            $filters[] = 'connector_type_name:' . $this->escapeFacet($data->filterConnectorType);
        }

        $numericFilters = array_values(array_filter(array_merge(
            $this->rangeToNumericFilters('price', $data->filterPrice ?? null),
            $this->rangeToNumericFilters('power_output', $data->filterPowerOutput ?? null),
            $this->rangeToNumericFilters('capacity', $data->filterCapacity ?? null),
        )));


        $builder = Products::search($data->searchTerm, function ($algolia, string $query, array $opts) use ($filters, $numericFilters, $page, $perPage) {
            if (!empty($filters)) {
                $opts['filters'] = implode(' AND ', $filters);
            }
            if (!empty($numericFilters)) {
                $opts['numericFilters'] = $numericFilters;
            }

            $opts['facets'] = ['type', 'manufacturer_name', 'connector_type_name'];

            $opts['page'] = max(0, $page - 1);
            $opts['hitsPerPage'] = $perPage;
            $opts['attributesToHighlight'] = ['title', 'description'];
            $opts['highlightPreTag'] = '<mark>';
            $opts['highlightPostTag'] = '</mark>';

            return $algolia->search($query, $opts);
        });

        $raw = $builder->raw();

        return SearchResult::from(
            [
                'query' => $data->searchTerm,
                'filters' => $filters,
                'numeric' => $numericFilters,
                'page' => Arr::get($raw, 'page', max(0, $page - 1)) + 1,
                'perPage' => $perPage,
                'total' => Arr::get($raw, 'nbHits', 0),
                'hits' => Arr::get($raw, 'hits', []),
                'raw' => $raw,
            ]
        );

    }


    /**
     * Escape facet value for Algolia
     * @param string $value
     * @return string
     */
    private function escapeFacet(string $value): string|null
    {
        return str_replace('"', '\"', $value);
    }

    /**
     * Convert range data to numeric filters
     * @param string $field
     * @param RangeData|null $range
     * @return array
     */
    private function rangeToNumericFilters(string $field, ?RangeData $range): array
    {
        if (!$range) {
            return [];
        }

        $out = [];

        if ($range->min) {
            $out[] = "{$field}>=" . (float)$range->min;
        }
        if ($range->max) {
            $out[] = "{$field}<=" . (float)$range->max;
        }

        return $out;
    }


}
