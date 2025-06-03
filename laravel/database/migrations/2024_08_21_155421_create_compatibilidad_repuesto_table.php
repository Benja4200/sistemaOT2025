<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompatibilidadRepuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compatibilidad_repuesto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cod_modelo');
            $table->unsignedBigInteger('cod_repuesto');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cod_modelo')->references('id')->on('modelo');
            $table->foreign('cod_repuesto')->references('id')->on('repuesto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compatibilidad_repuesto');
    }
}
