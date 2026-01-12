<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterStrategy
{
    public function apply(Builder $query, string $attributeKey, array $config): void;
}
