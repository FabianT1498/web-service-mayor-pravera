<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDollarDenominationRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('dollar_denomination_records', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->bigInteger('cash_register_data_id');
            $table->integer('denominations_id');
        
            $table->foreign('cash_registers_data_id')->references('id')->on('cash_registers_data');
            $table->foreign('denominations_id')->references('id')->on('denominations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('caja_mayorista')->dropIfExists('dollar_denomination_records');
    }
}
