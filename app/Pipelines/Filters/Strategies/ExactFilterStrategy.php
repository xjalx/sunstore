<?php

namespace App\Pipelines\Filters\Strategies;

use App\Contracts\FilterStrategy;
use Illuminate\Database\Eloquent\Builder;

class ExactFilterStrategy implements FilterStrategy
{
    public function apply(Builder $query, string $attributeKey, array $config): void
    {
        $value = $config['value'] ?? null;

        if ($value === null || $value === '') {
            return;
        }

        $query->whereHas('attributes', function (Builder $q) use ($attributeKey, $value) {
            $q->where('attribute_key', $attributeKey)
              ->where('string_value', $value);
        });
    }
}
