<?php

use App\Http\Controllers\CashRegisterController;
use Illuminate\Support\Facades\Route;

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

    Route::get('cash_register/create-step-one', [CashRegisterController::class, 'createStepOne'])->name('cash_register_step_one.create');
    Route::post('cash_register/post-step-one', [CashRegisterController::class, 'postCreateStepOne'])->name('cash_register_step_one.post');
        
    Route::get('cash_register/create-step-two', [CashRegisterController::class, 'createStepTwo'])->name('cash_register_step_two.create');
    Route::get('cash_register/create-step-three', [CashRegisterController::class, 'createStepThree'])->name('cash_register_step_three.create');
    Route::get('cash_register/create-step-four', [CashRegisterController::class, 'createStepFour'])->name('cash_register_step_four.create');
    Route::get('cash_register/create-step-five', [CashRegisterController::class, 'createStepFive'])->name('cash_register_step_five.create');
    Route::get('cash_register/create-step-six', [CashRegisterController::class, 'createStepSix'])->name('cash_register_step_six.create');  
    Route::post('cash_register/store', [CashRegisterController::class, 'store'])->name('cash_register.store');
});

require __DIR__.'/auth.php';
