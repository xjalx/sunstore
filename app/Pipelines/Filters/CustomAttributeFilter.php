<?php

namespace App\Pipelines\Filters;

use App\Enums\ProductType;
use App\Pipelines\Filters\Strategies\FilterStrategyFactory;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class CustomAttributeFilter implements Filter
{
    public function __construct(
        private readonly array $customAttributes,
        private readonly ?string $productType = null
    ) {}

    public function handle(Builder $query, Closure $next): Builder
    {
        if ($this->productType === null) {
            return $next($query);
        }

        $type = ProductType::tryFrom($this->productType);
        if ($type === null) {
            return $next($query);
        }

        $allowedAttributes = $type->filterableAttributes();
        $allowedKeys = array_map(fn($attr) => $attr->value, $allowedAttributes);

        foreach ($this->customAttributes as $key => $config) {
            if (!in_array($key, $allowedKeys, true)) {
                continue;
            }

            $strategyType = $config['type'] ?? null;
            if ($strategyType === null) {
                continue;
            }

            $strategy = FilterStrategyFactory::makeFromString($strategyType);
            $strategy->apply($query, $key, $config);
        }

        return $next($query);
    }
}
