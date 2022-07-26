<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BillsPayableController;
use App\Http\Controllers\SchedulePayableController;

Route::group(['middleware' => ['auth']], function () {

    // ----- Bills Payable -----
    Route::get('bill_payable', [BillsPayableController::class, 'index'])->name('bill_payable.index');
    Route::get('bill_payable/detail/{numero_d}/{cod_prov}', [BillsPayableController::class, 'show'])->name('bill_payable.showBillPayable');
    Route::post('bill_payable/payment', [BillsPayableController::class, 'storePayment'])->name('bill_payable.store-payment');
    Route::put('bill_payable/tasa', [BillsPayableController::class, 'updateBillPayableTasa'])->name('bill_payable.update-tasa');

    // ----- Schedules -----
    Route::get('schedule', [SchedulePayableController::class, 'index'])->name('schedule.index');
    Route::get('schedule/create', [SchedulePayableController::class, 'create'])->name('schedule.create');
    Route::get('schedule/show/{id}', [SchedulePayableController::class, 'show'])->name('schedule.show');

    Route::post('schedule', [SchedulePayableController::class, 'store'])->name('schedule.store');

    // ----- Bills Payable Group -----
    Route::post('bill_payable_group/payment', [BillsPayableController::class, 'storeBillPayableGroupPayment'])->name('bill_payable_groups.store-payment');
    Route::get('bill_payable_group', [BillsPayableController::class, 'billPayableGroupsIndex'])->name('bill_payable_groups.index');
    Route::get('bill_payable_group/detail/{id}', [BillsPayableController::class, 'showBillPayableGroup'])->name('bill_payable.showBillPayableGroup');
    Route::put('bill_payable_group/tasa', [BillsPayableController::class, 'updateBillPayableGroupTasa'])->name('bill_payable_groups.update-tasa');

});

Route::group(['middleware' => ['auth', 'jsonify']], function() {
    
    // ----- Bill Payable Group -----
    Route::put('bill_payable/group/{id}', [BillsPayableController::class, 'updateBillPayableGroup']);
    Route::get('bill_payable/group/id/{id}', [BillsPayableController::class, 'getBillPayableGroupByID']);
    Route::get('bill_payable/group/{cod_prov}', [BillsPayableController::class, 'getBillPayableGroups']);
    Route::post('bill_payable/group', [BillsPayableController::class, 'storeBillPayableGroup']); 
    
    Route::post('bill_payable/group/schedule', [BillsPayableController::class, 'linkBillPayableGroupToSchedule']);

    // ----- Bills Payable -----
    Route::get('bill_payable/{cod_prov}/{numero_d}', [BillsPayableController::class, 'getBillPayable']);
    Route::post('bill_payable/', [BillsPayableController::class, 'storeBillPayable']);
    Route::post('bill_payable/{schedule_id}', [BillsPayableController::class, 'linkBillPayableToSchedule']);
  
    // ----- Schedules -----
    Route::get('schedule/{id}', [SchedulePayableController::class, 'getSchedule']);

    
    // ----- Providers -----
    Route::get('provider', [BillsPayableController::class, 'getProviders']); 
});
