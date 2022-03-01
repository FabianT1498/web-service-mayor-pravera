<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointSaleBsRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('point_sale_bs_records', function (Blueprint $table) {
            $table->id('id');
            $table->float('amount');
            $table->string('type', 50);
            $table->unsignedBigInteger('cash_register_data_id');
            $table->string('bank_name', 100);
      
        
            $table->foreign('cash_register_data_id')->references('id')->on('cash_register_data');
            $table->foreign('bank_name')->references('name')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_sale_bs_records');
    }
}
