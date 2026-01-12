<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Services\ProductFilterService;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    public function __construct(
        private ProductFilterService $filterService
    ) {}

    public function index(ProductFilterRequest $request): JsonResponse
    {
        $filterDTO = $request->toDTO();
        $products = $this->filterService->filter($filterDTO);

        $transformedProducts = $products->through(function ($product) {
            return [
                'id' => $product->id,
                'type' => $product->type,
                'name' => $product->name,
                'manufacturer' => $product->manufacturer,
                'price' => $product->price,
                'description' => $product->description,
                'attributes' => $product->getCustomAttributes(),
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json([
            'data' => $transformedProducts->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ],
            'links' => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ],
            'filters' => $filterDTO->toArray(),
        ]);
    }

    public function manufacturers(): JsonResponse
    {
        return response()->json([
            'data' => $this->filterService->getManufacturers(),
        ]);
    }

    public function connectorTypes(): JsonResponse
    {
        return response()->json([
            'data' => $this->filterService->getConnectorTypes(),
        ]);
    }
}
