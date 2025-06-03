<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispositivoOtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispositivo_ot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cod_dispositivo');
            $table->unsignedBigInteger('cod_ot');
            $table->timestamps();
            $table->softDeletes(); // Agrega la columna deleted_at para soporte de borrado suave

            $table->foreign('cod_dispositivo')
                ->references('id')
                ->on('dispositivo')
                ->onDelete('cascade');

            $table->foreign('cod_ot')
                ->references('numero_ot')
                ->on('ot')
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
        Schema::dropIfExists('dispositivo_ot');
    }
}
