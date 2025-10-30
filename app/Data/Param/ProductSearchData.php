<?php

namespace App\Data\Param;

use Spatie\LaravelData\Data;

class ProductSearchData extends Data
{
    public function __construct(
        public ?string    $searchTerm,

        public ?string    $filterProductType,
        public ?string    $filterManufacturer,
        public ?string    $filterConnectorType,

        public ?RangeData $filterPrice,
        public ?RangeData $filterPowerOutput,
        public ?RangeData $filterCapacity,

    )
    {
    }
}
