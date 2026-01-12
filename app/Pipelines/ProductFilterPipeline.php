<?php

namespace App\Pipelines;

use App\DTO\ProductFilterDTO;
use App\Pipelines\Filters\CustomAttributeFilter;
use App\Pipelines\Filters\DescriptionFilter;
use App\Pipelines\Filters\ManufacturerFilter;
use App\Pipelines\Filters\NameFilter;
use App\Pipelines\Filters\PriceRangeFilter;
use App\Pipelines\Filters\TypeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class ProductFilterPipeline
{
    public function __construct(
        private readonly ProductFilterDTO $filterDTO
    ) {}

    public function apply(Builder $query): Builder
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($this->getFilters())
            ->thenReturn();
    }

    private function getFilters(): array
    {
        return [
            new TypeFilter($this->filterDTO->type),
            new NameFilter($this->filterDTO->name),
            new ManufacturerFilter($this->filterDTO->manufacturer),
            new PriceRangeFilter($this->filterDTO->minPrice, $this->filterDTO->maxPrice),
            new DescriptionFilter($this->filterDTO->description),
            new CustomAttributeFilter($this->filterDTO->customAttributes, $this->filterDTO->type),
        ];
    }
}
