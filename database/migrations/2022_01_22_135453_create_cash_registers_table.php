<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegistersTable extends Migration
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
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->date('date');
            $table->string('cash_register_id');
            $table->integer('cash_register_worker');
            $table->float('liquid_money_dollars');
            $table->float('liquid_money_bs');
            $table->float('payment_zelle');
            $table->float('debit_card_payment_bs');
            $table->float('debit_card_payment_dollar');

            $table->foreign('user_id')->references('id')->on('users');
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
