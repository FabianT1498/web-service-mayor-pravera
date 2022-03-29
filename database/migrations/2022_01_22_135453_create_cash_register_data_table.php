<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegisterDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('cash_register_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->string('cash_register_user', 50);
            $table->unsignedBigInteger('worker_id');
            $table->enum('status', array_values(config('constants.CASH_REGISTER_STATUS')));
            $table->date('date');
            $table->timestamps();
     
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('worker_id')->references('id')->on('workers');
            $table->foreign('cash_register_user')->references('name')->on('cash_register_users');

            $table->unique(['date', 'cash_register_user']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('caja_mayorista')->dropIfExists('cash_register_data');
    }
}
