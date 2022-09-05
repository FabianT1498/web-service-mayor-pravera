<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsPayablePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_payable_payments', function (Blueprint $table) {
            $table->string('nro_doc', 50);
            $table->string('cod_prov', 50);
            $table->bigInteger('bill_payments_id', false, true);

        
            $table->foreign(['nro_doc', 'cod_prov'])
                ->references(['nro_doc', 'cod_prov'])->on('bills_payable'); 
                
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
        Schema::dropIfExists('bills_payable_payments');
    }
}
