<?php

namespace App\Pipelines\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TypeFilter implements Filter
{
    public function __construct(private readonly ?string $type) {}

    public function handle(Builder $query, Closure $next): Builder
    {
        if ($this->type) {
            $query->where('type', $this->type);
        }

        return $next($query);
    }
}
