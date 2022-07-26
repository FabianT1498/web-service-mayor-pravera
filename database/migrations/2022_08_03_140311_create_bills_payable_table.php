<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsPayableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_payable', function (Blueprint $table) {
            $table->string('nro_doc', 50);
            $table->string('cod_prov', 50);
            $table->string('descrip_prov', 250);
            $table->enum('bill_type', array_keys(config('constants.BILL_PAYABLE_TYPE')));
            $table->decimal('amount', 28, 2);
            $table->decimal('tasa', 28, 2);
            $table->boolean('is_dollar');
            $table->enum('status', array_keys(config('constants.BILL_PAYABLE_STATUS')));
            $table->date('emission_date');
            $table->bigInteger('bill_payable_schedules_id', false, true)->nullable(true);
            $table->bigInteger('bill_payable_groups_id', false, true)->nullable(true);
            
            $table->primary(['nro_doc', 'cod_prov']);
            $table->foreign('bill_payable_schedules_id')->references('id')->on('bill_payable_schedules');
            $table->foreign('bill_payable_groups_id')->references('id')->on('bill_payable_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills_payable');
    }
}
