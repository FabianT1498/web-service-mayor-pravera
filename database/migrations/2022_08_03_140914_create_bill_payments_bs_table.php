<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPaymentsBsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payments_bs', function (Blueprint $table) {
            $table->unsignedBigInteger('bill_payments_id');
            
            $table->string('bank_name', 100);
            $table->string('ref_number', 50);
            $table->decimal('tasa', 28, 4);

            $table->foreign('bill_payments_id')
                ->references('id')
                ->on('bill_payments');

            $table->unique(['bank_name', 'ref_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_payments_bs');
    }
}
