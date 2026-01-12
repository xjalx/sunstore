<?php

use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/products', [ProductApiController::class, 'index'])->name('api.products.index');
    Route::get('/manufacturers', [ProductApiController::class, 'manufacturers'])->name('api.manufacturers');
    Route::get('/connector-types', [ProductApiController::class, 'connectorTypes'])->name('api.connector-types');
});
