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
            $table->string('nro_doc', 50);
            $table->string('cod_prov', 50);
            $table->decimal('amount', 28, 4);
            $table->string('bank_name', 100);
            $table->string('ref_number', 50);
            $table->date('date');
            $table->boolean('is_dollar');
            $table->decimal('tasa', 28, 4);

            $table->foreign(['nro_doc', 'cod_prov'])
                ->references(['nro_doc', 'cod_prov'])->on('bills_payable');

            $table->unique(['nro_doc', 'cod_prov', 'ref_number']);
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
