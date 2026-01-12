<?php

namespace App\DTO;

readonly class ProductFilterDTO
{
    public function __construct(
        public ?string $type = null,
        public ?string $name = null,
        public ?string $manufacturer = null,
        public ?float $minPrice = null,
        public ?float $maxPrice = null,
        public ?string $description = null,
        public array $customAttributes = [],
        public int $page = 1,
        public int $perPage = 20,
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'manufacturer' => $this->manufacturer,
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice,
            'description' => $this->description,
            'custom_attributes' => $this->customAttributes,
            'page' => $this->page,
            'per_page' => $this->perPage,
        ];
    }
}
