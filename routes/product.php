<?php

use App\Http\Controllers\ProductsController;

Route::group(['middleware' => ['auth']], function () {
    // Cash register routes
    Route::get('products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('products_suggestions', [ProductsController::class, 'getProductsWithSuggestions'])->name('products.productsSuggestions');
});

Route::group(['middleware' => ['auth', 'jsonify']], function() {
    Route::get('products/suggestions/{codProduct}', [ProductsController::class, 'getProductSuggestions']);
    Route::post('products/suggestions', [ProductsController::class, 'storeProduct']);
});
