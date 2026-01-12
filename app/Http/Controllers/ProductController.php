<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use App\Services\ProductFilterService;

class ProductController extends Controller
{
    public function __construct(
        private ProductFilterService $filterService
    ) {}

    public function index(ProductFilterRequest $request)
    {
        $filterDTO = $request->toDTO();
        $products = $this->filterService->filter($filterDTO);
        $manufacturers = $this->filterService->getManufacturers();
        $connectorTypes = $this->filterService->getConnectorTypes();
        $type = $request->input('type', 'all');

        return view('products.index', compact('products', 'manufacturers', 'connectorTypes', 'type'));
    }
}
