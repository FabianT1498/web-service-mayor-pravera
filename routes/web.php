<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// ---- CASH REGISTER ROUTES  ---
require __DIR__.'/cash-register.php';

// ---- PRODUCT ROUTES  ---
require __DIR__.'/product.php';

require __DIR__.'/auth.php';
