<?php

namespace App\Data\Result;

use Spatie\LaravelData\Data;

class SearchResult extends Data
{

    public function __construct(
        public ?string $query,
        public ?array  $filters,
        public ?array  $numeric,
        public ?int    $page,
        public ?int    $perPage,
        public ?int    $total,
        public ?array  $hits,
        public ?array  $raw,
    ) {}

}
