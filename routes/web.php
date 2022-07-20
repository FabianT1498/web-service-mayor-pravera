<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => ['auth']], function () {
    // Cash register routes
    Route::get('products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('products_suggestions', [ProductsController::class, 'getProductsWithSuggestions'])->name('products.productsSuggestions');
});

Route::group(['middleware' => ['auth', 'jsonify']], function() {
    Route::get('products/suggestions/{codProduct}', [ProductsController::class, 'getProductSuggestions']);
    Route::post('products/suggestions', [ProductsController::class, 'storeProduct']);
});

require __DIR__.'/auth.php';
