<?php

namespace App\Data\Param;

use Spatie\LaravelData\Data;

class RangeData extends Data
{
    public function __construct(
        public ?string $min,
        public ?string $max,
    ) {}
}
