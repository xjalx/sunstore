<?php

namespace App\Pipelines\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class DescriptionFilter implements Filter
{
    public function __construct(private readonly ?string $description) {}

    public function handle(Builder $query, Closure $next): Builder
    {
        if ($this->description) {
            $query->where('description', 'like', '%' . $this->description . '%');
        }

        return $next($query);
    }
}
