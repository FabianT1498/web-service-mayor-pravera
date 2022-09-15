<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('web_services_db')->create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 250);
            $table->string('description', 500);
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
        Schema::connection('web_services_db')->dropIfExists('notes');
    }
}
