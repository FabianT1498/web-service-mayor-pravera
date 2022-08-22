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
            $table->unsignedBigInteger('bill_payments_id');
           
            $table->enum('payment_method', array_keys(config('constants.FOREIGN_CURRENCY_BILL_PAYMENT_METHODS')));
            $table->date('retirement_date');

            $table->foreign('bill_payments_id')
                ->references('id')
                ->on('bill_payments');
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
