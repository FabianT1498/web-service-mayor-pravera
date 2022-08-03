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
            $table->string('nro_doc', 50);
            $table->string('cod_prov', 50);
            $table->enum('bill_type', array_keys(config('constants.BILL_PAYABLE_TYPE')));

            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', array_keys(config('constants.BILL_PAYABLE_SCHEDULE_STATUS')));

            $table->foreign(['nro_doc', 'cod_prov', 'bill_type'])
                ->references(['nro_doc', 'cod_prov', 'bill_type'])->on('bills_payable');

            $table->unique(['nro_doc', 'cod_prov', 'bill_type']);
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
