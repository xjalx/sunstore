<?php

namespace App\Pipelines\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ManufacturerFilter implements Filter
{
    public function __construct(private readonly ?string $manufacturer) {}

    public function handle(Builder $query, Closure $next): Builder
    {
        if ($this->manufacturer) {
            $query->where('manufacturer', 'like', '%' . $this->manufacturer . '%');
        }

        return $next($query);
    }
}
