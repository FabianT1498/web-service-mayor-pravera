<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDenominations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('caja_mayorista')->create('denominations', function (Blueprint $table) {
            $table->id();
            $table->string('currency', 20);
            $table->float('denomination');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('caja_mayorista')->dropIfExists('denominations');
    }
}
