<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->string('nro_doc', 50);
            $table->string('cod_prov', 50);
            $table->decimal('amount', 28, 2);
            $table->date('date');
            $table->boolean('is_dollar');

            $table->foreign(['nro_doc', 'cod_prov'])
                ->references(['nro_doc', 'cod_prov'])->on('bills_payable');

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_payments');
    }
}
