<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('web_services_db')->create('payment_methods', function (Blueprint $table) {
            $table->string('CodPago');
            $table->string('Descrip');

            $table->primary('CodPago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('web_services_db')->dropIfExists('payment_methods');
    }
}
