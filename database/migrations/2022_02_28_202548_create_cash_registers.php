<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegisters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('cash_registers', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->float('total_dollar_cash');
            $table->float('total_bs_cash');
            $table->float('total_dollar_denominations');
            $table->float('total_bs_denominations');
            $table->float('total_point_sale_bs');
            $table->float('total_point_sale_dollar');
            $table->float('total_zelle');
        
            $table->foreign('id')->references('id')->on('cash_registers_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('caja_mayorista')->dropIfExists('cash_registers');
    }
}
