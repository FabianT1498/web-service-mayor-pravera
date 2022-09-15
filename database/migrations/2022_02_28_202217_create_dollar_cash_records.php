<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDollarCashRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('web_services_db')->create('dollar_cash_records', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->unsignedBigInteger('cash_register_data_id');
      
        
            $table->foreign('cash_register_data_id')
                ->references('id')
                ->on('cash_register_data')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('web_services_db')->dropIfExists('dollar_cash_records');;
    }
}
