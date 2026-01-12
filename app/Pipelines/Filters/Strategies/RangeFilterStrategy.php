<?php

namespace App\Pipelines\Filters\Strategies;

use App\Contracts\FilterStrategy;
use Illuminate\Database\Eloquent\Builder;

class RangeFilterStrategy implements FilterStrategy
{
    public function apply(Builder $query, string $attributeKey, array $config): void
    {
        $min = $config['min'] ?? null;
        $max = $config['max'] ?? null;

        if ($min === null && $max === null) {
            return;
        }

        $query->whereHas('attributes', function (Builder $q) use ($attributeKey, $min, $max) {
            $q->where('attribute_key', $attributeKey);

            if ($min !== null) {
                $q->where('numeric_value', '>=', $min);
            }
            if ($max !== null) {
                $q->where('numeric_value', '<=', $max);
            }
        });
    }
}
