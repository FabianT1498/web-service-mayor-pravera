<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CashRegisterWorkerController;
use App\Http\Controllers\DollarExchangeController;
use App\Http\Controllers\BankController;

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
    Route::get('cash_register', [CashRegisterController::class, 'index'])->name('cash_register.index');
    Route::get('cash_register/create', [CashRegisterController::class, 'create'])->name('cash_register.create');
    Route::post('cash_register', [CashRegisterController::class, 'store'])->name('cash_register.store');
    Route::get('cash_register/edit/{id}', [CashRegisterController::class, 'edit'])->name('cash_register.edit');
    Route::put('cash_register/{id}', [CashRegisterController::class, 'update'])->name('cash_register.update');
    Route::put('cash_register/{id}/finish', [CashRegisterController::class, 'finishCashRegister'])->name('cash_register.finish');
    
    // Route::get('cash_register/dollar-cash-detail', [CashRegisterController::class, 'createStepTwo'])->name('cash_register_step_two.create');
    // Route::get('cash_register/create-step-three', [CashRegisterController::class, 'createStepThree'])->name('cash_register_step_three.create');
    // Route::get('cash_register/create-step-four', [CashRegisterController::class, 'createStepFour'])->name('cash_register_step_four.create');
    // Route::get('cash_register/create-step-five', [CashRegisterController::class, 'createStepFive'])->name('cash_register_step_five.create');
    // Route::get('cash_register/create-step-six', [CashRegisterController::class, 'createStepSix'])->name('cash_register_step_six.create');  
    // Route::get('cash_register/create-step-seven', [CashRegisterController::class, 'createStepSeven'])->name('cash_register_step_seven.create');
    // Route::get('cash_register/create-step-eight', [CashRegisterController::class, 'createStepEight'])->name('cash_register_step_eight.create');  
    // Route::get('cash_register/create-step-nine', [CashRegisterController::class, 'createStepNine'])->name('cash_register_step_nine.create');  

    // Route::post('cash_register/store', [CashRegisterController::class, 'store'])->name('cash_register.store');

});

Route::group(['middleware' => ['auth', 'jsonify']], function() {
    Route::post('dollar_exchange', [DollarExchangeController::class, 'store']);
    Route::get('dollar_exchange', [DollarExchangeController::class, 'get']);
    
    Route::get('banks', [BankController::class, 'getAll']);
});

require __DIR__.'/auth.php';
