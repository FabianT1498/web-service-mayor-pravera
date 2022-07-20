<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::connection('estadisticas_productos')->create('product_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('cod_prod', 50);
            $table->float('percent_suggested');
            $table->string('user_name', 50);
            $table->enum('status', array_values(config('constants.SUGGESTION_STATUS')));
            $table->timestamps();
            $table->foreign('cod_prod')->references('cod_prod')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::connection('estadisticas_productos')->dropIfExists('product_suggestions');
    }
}
