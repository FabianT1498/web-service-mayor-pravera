<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointSaleDollarRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('point_sale_dollar_records', function (Blueprint $table) {
            $table->id('id');
            $table->float('amount');
            $table->string('point_sale_user');
            $table->timestamp('completed_at');
            $table->bigInteger('cash_register_data_id');
            
            $table->foreign('cash_registers_data_id')->references('id')->on('cash_registers_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_sale_dollar_records');
    }
}
