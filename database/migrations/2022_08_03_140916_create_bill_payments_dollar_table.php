<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPaymentsDollarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payments_dollar', function (Blueprint $table) {
            $table->string('nro_doc', 50);
            $table->string('cod_prov', 50);
           

            $table->enum('payment_method', array_keys(config('constants.FOREIGN_CURRENCY_BILL_PAYMENT_METHODS')));
            $table->date('retirement_date');

            $table->foreign(['nro_doc', 'cod_prov'])
                ->references(['nro_doc', 'cod_prov'])->on('bill_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_payments_dollar');
    }
}
