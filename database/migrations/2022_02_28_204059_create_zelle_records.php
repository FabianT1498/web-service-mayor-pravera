<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZelleRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('zelle_records', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
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
        Schema::connection('caja_mayorista')->dropIfExists('zelle_records');;
    }
}
