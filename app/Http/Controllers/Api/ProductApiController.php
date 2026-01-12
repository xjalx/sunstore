<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Resources\ProductCollection;
use App\Services\ProductFilterService;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    public function __construct(
        private ProductFilterService $filterService
    ) {}

    public function index(ProductFilterRequest $request): ProductCollection
    {
        $filterDTO = $request->toDTO();
        $products = $this->filterService->filter($filterDTO);

        return (new ProductCollection($products))->withFilters($filterDTO);
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
