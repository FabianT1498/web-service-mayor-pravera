<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoMovilBsRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('pago_movil_bs_records', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
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
        Schema::connection('caja_mayorista')->dropIfExists('pago_movil_bs_records');

    }
}