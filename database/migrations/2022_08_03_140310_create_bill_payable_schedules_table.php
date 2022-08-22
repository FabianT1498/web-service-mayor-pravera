<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPayableSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payable_schedules', function (Blueprint $table) {
            $table->id();
          
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', array_keys(config('constants.BILL_PAYABLE_SCHEDULE_STATUS')));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_payable_schedules');
    }
}
