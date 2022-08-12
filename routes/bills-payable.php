<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BillsPayableController;
use App\Http\Controllers\SchedulePayableController;

Route::group(['middleware' => ['auth']], function () {

    // ----- Bills Payable -----
    Route::get('bill_payable', [BillsPayableController::class, 'index'])->name('bill_payable.index');
    // Route::get('bill_payable/{schedule_id}', [BillsPayableController::class, 'getBillPayableToSchedule'])->name('bill_payable.getBillsPayableFromSchedule');

    // ----- Schedules -----
    Route::get('schedule', [SchedulePayableController::class, 'index'])->name('schedule.index');
    Route::get('schedule/create', [SchedulePayableController::class, 'create'])->name('schedule.create');

    Route::post('schedule', [SchedulePayableController::class, 'store'])->name('schedule.store');

});

Route::group(['middleware' => ['auth', 'jsonify']], function() {

    // ----- Bills Payable -----
    Route::get('bill_payable/{cod_prov}/{numero_d}', [BillsPayableController::class, 'getBillPayable']);
    Route::post('bill_payable/', [BillsPayableController::class, 'storeBillPayable']);
    Route::post('bill_payable/{schedule_id}', [BillsPayableController::class, 'linkBillPayableToSchedule']);
    
    // ----- Schedules -----
    Route::get('schedule/{id}', [SchedulePayableController::class, 'getSchedule']);

    
});
