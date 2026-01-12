<?php

namespace App\Pipelines\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    public function handle(Builder $query, Closure $next): Builder;
}
