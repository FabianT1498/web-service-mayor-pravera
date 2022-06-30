<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoticketBsRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todoticket_bs_records', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->unsignedBigInteger('cash_register_data_id');
      
        
            $table->foreign('cash_register_data_id')->references('id')
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
        Schema::dropIfExists('todoticket_bs_records');
    }
}
