<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialPreciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_precios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_productos')->unsigned();
            $table->decimal('precio', 10, 2);


            $table->foreign('id_productos')->references('id')->on('productos');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_precios');
    }
}
