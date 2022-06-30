<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->string('worker_name', 100);
            $table->string('user_name');
            $table->string('cash_register_user');
            $table->float('total_dollar_cash');
            $table->float('total_amex');
            $table->float('total_todoticket');
            $table->float('total_pago_movil_bs');
            $table->float('total_dollar_denominations');
            $table->float('total_bs_denominations');
            $table->float('total_point_sale_bs');
            $table->float('total_point_sale_dollar');
            $table->float('total_zelle');
            $table->date('date');
            $table->timestamps();
            $table->unsignedBigInteger('cash_register_data_id');
        
            $table->foreign('cash_register_data_id')->references('id')->on('cash_register_data');
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
