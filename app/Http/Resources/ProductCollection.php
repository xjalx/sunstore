<?php

namespace App\Http\Resources;

use App\DTO\ProductFilterDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public $collects = ProductResource::class;

    private ?ProductFilterDTO $filterDTO = null;

    public function withFilters(ProductFilterDTO $filterDTO): self
    {
        $this->filterDTO = $filterDTO;
        return $this;
    }

    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        $custom = [
            'meta' => [
                'current_page' => $paginated['current_page'],
                'from' => $paginated['from'],
                'to' => $paginated['to'],
                'per_page' => $paginated['per_page'],
                'total' => $paginated['total'],
                'last_page' => $paginated['last_page'],
            ],
            'links' => [
                'first' => $paginated['first_page_url'],
                'last' => $paginated['last_page_url'],
                'prev' => $paginated['prev_page_url'],
                'next' => $paginated['next_page_url'],
            ],
        ];

        if ($this->filterDTO) {
            $custom['filters'] = $this->filterDTO->toArray();
        }

        return $custom;
    }
}
