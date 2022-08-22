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
            $table->string('nro_doc', 50);
            $table->string('cod_prov', 50);
        
            $table->string('bank_name', 100);
            $table->string('ref_number', 50);
            $table->decimal('tasa', 28, 4);

            $table->foreign(['nro_doc', 'cod_prov'])
                ->references(['nro_doc', 'cod_prov'])->on('bill_payments');

            $table->primary(['nro_doc', 'cod_prov', 'bank_name', 'ref_number']);
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
