<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPayableGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payable_groups', function (Blueprint $table) {
            $table->id();
            $table->string('cod_prov', 50);
            $table->enum('status', array_keys(config('constants.BILL_PAYABLE_STATUS')));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_payable_groups');
    }
}
