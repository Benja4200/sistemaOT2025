<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleDispositivoOtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_dispositivo_ot', function (Blueprint $table) {
            $table->id();
            $table->text('rayones_det');
            $table->text('rupturas_det');
            $table->text('tornillos_det');
            $table->text('gomas_det');
            $table->text('estado_dispositivo_det');
            $table->text('observaciones_det');
            $table->unsignedBigInteger('cod_dispositivo_ot');
            $table->timestamps();
            $table->softDeletes(); // Agrega la columna deleted_at

            $table->foreign('cod_dispositivo_ot')
                ->references('id')
                ->on('dispositivo_ot')
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
        Schema::dropIfExists('detalle_dispositivo_ot');
    }
}
