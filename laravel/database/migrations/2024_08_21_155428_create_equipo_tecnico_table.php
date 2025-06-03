<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipoTecnicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipo_tecnico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cod_tecnico');
            $table->unsignedBigInteger('cod_ot');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cod_tecnico')->references('id')->on('tecnico')->onDelete('cascade');
            $table->foreign('cod_ot')->references('numero_ot')->on('ot')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipo_tecnico');
    }
}
