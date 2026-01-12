<?php

namespace App\Services;

use App\DTO\ProductFilterDTO;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Pipelines\ProductFilterPipeline;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductFilterService
{
    public function filter(ProductFilterDTO $filterDTO): LengthAwarePaginator
    {
        $query = Product::query()->with('attributes');

        $pipeline = new ProductFilterPipeline($filterDTO);
        $query = $pipeline->apply($query);

        return $query->orderBy('name')->paginate($filterDTO->perPage);
    }

    public function getManufacturers(): Collection
    {
        return Product::distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
    }

    public function getConnectorTypes(): Collection
    {
        return ProductAttribute::where('attribute_key', 'connector_type')
            ->whereNotNull('string_value')
            ->distinct()
            ->orderBy('string_value')
            ->pluck('string_value');
    }
}
