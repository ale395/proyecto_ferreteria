<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCobranzaCompsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobranza_comp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cobranza_cab_id')->unsigned();
            $table->char('tipo_comp', 2);
            $table->integer('comp_im')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cobranza_comp');
    }
}
