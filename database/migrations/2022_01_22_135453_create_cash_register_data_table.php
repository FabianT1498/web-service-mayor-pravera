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
        Schema::connection('web_services_db')->create('cash_register_data', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('cash_register_user', 50);
            $table->unsignedBigInteger('worker_id');
            $table->enum('status', array_values(config('constants.CASH_REGISTER_STATUS')));
            $table->date('date');
            $table->timestamps();
     
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
        Schema::connection('web_services_db')->dropIfExists('cash_register_data');
    }
}
