<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispositivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispositivo', function (Blueprint $table) {
            $table->id();
            $table->string('numero_serie_dispositivo', 20);
            $table->unsignedBigInteger('cod_modelo');
            $table->unsignedBigInteger('cod_sucursal');
            $table->timestamps();
            $table->softDeletes(); // Agrega la columna deleted_at para soporte de borrado suave

            $table->foreign('cod_modelo')
                ->references('id')
                ->on('modelo')
                ->onDelete('cascade');

            $table->foreign('cod_sucursal')
                ->references('id')
                ->on('sucursal')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispositivo');
    }
}
