<?php

namespace App\Pipelines\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class PriceRangeFilter implements Filter
{
    public function __construct(
        private readonly ?float $minPrice,
        private readonly ?float $maxPrice
    ) {}

    public function handle(Builder $query, Closure $next): Builder
    {
        if ($this->minPrice !== null) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice !== null) {
            $query->where('price', '<=', $this->maxPrice);
        }

        return $next($query);
    }
}
