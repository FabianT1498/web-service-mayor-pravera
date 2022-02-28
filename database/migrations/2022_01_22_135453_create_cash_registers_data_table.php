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
        Schema::connection('caja_mayorista')->create('cash_registers_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->string('cash_register_user');
            $table->string('worker_name');
            $table->enum('status', ['EN EDICION', 'COMPLETADO']);
            $table->timestamp('created_at');
        
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
        Schema::connection('caja_mayorista')->dropIfExists('cash_registers_data');
    }
}
