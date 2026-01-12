<?php

namespace App\Pipelines\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class NameFilter implements Filter
{
    public function __construct(private readonly ?string $name) {}

    public function handle(Builder $query, Closure $next): Builder
    {
        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        return $next($query);
    }
}
