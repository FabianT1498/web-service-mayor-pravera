<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\MoneyEntranceController;
use App\Http\Controllers\DrinkBillController;
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
    // Money entrance routes
    Route::get('money_entrance', [MoneyEntranceController::class, 'index'])->name('money_entrance.index');
    Route::get('money_entrance/generate-report', [MoneyEntranceController::class, 'generateReport'])->name('money_entrance.generate-report');

    // Drink Bill routes
    Route::get('drink_bill', [DrinkBillController::class, 'index'])->name('drink-bills.index');
    Route::get('drink_bill/generate-report', [DrinkBillController::class, 'generateReport'])->name('drink-bills.generate-report');

    // Cash register routes
    Route::get('cash_register', [CashRegisterController::class, 'index'])->name('cash_register.index');
    Route::get('cash_register/create', [CashRegisterController::class, 'create'])->name('cash_register.create');
    Route::post('cash_register', [CashRegisterController::class, 'store'])->name('cash_register.store');
    Route::get('cash_register/edit/{id}', [CashRegisterController::class, 'edit'])->name('cash_register.edit');
    Route::put('cash_register/{id}', [CashRegisterController::class, 'update'])->name('cash_register.update');
    Route::put('cash_register/{id}/finish', [CashRegisterController::class, 'finishCashRegister'])->name('cash_register.finish');

    Route::get('cash_register/pdf/{id}', [CashRegisterController::class, 'singleRecordPdf'])->name('cash_register.single_record_pdf');

    Route::get('cash_register/pdf/{id}', [CashRegisterController::class, 'singleRecordPdf'])->name('cash_register.single_record_pdf');
    Route::get('cash_register/pdf/{start_date}/{end_date}', [CashRegisterController::class, 'intervalRecordPdf'])->name('cash_register.interval_record_pdf');

    

  });

Route::group(['middleware' => ['auth', 'jsonify']], function() {
    Route::post('dollar_exchange', [DollarExchangeController::class, 'store']);
    Route::get('dollar_exchange', [DollarExchangeController::class, 'get']);

    Route::get('banks', [BankController::class, 'getAll']);

    Route::get('cash_register/users_without_record/{date}', [CashRegisterController::class, 'getCashRegisterUsersWithoutRecord']);

    Route::get('cash_register/saint/totals/{user}/{start_date}/{end_date}', [CashRegisterController::class, 'getTotalsFromSaint']);

    Route::get('cash_register/totals/{id}', [CashRegisterController::class, 'getTotals']);

});

require __DIR__.'/auth.php';
