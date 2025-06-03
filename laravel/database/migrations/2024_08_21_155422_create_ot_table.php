<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ot', function (Blueprint $table) {
            $table->id('numero_ot');
            $table->integer('horas_ot');
            $table->string('descripcion_ot', 1000);
            $table->string('comentario_ot', 2000)->nullable();
            $table->string('cotizacion', 50)->nullable();
            $table->unsignedBigInteger('cod_tipo_ot');
            $table->unsignedBigInteger('cod_prioridad_ot');
            $table->unsignedBigInteger('cod_estado_ot')->default(1);
            $table->unsignedBigInteger('cod_tipo_visita');
            $table->unsignedBigInteger('cod_servicio');
            $table->unsignedBigInteger('cod_contacto');
            $table->unsignedBigInteger('cod_tecnico_encargado');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('fecha_fin_planificada_ot')->nullable();

            // Foreign key constraints
            $table->foreign('cod_prioridad_ot')->references('id')->on('prioridad_ot')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cod_contacto')->references('id')->on('contacto')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cod_tecnico_encargado')->references('id')->on('tecnico')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cod_estado_ot')->references('id')->on('estado_ot')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cod_servicio')->references('id')->on('servicio')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cod_tipo_ot')->references('id')->on('tipo_ot')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cod_tipo_visita')->references('id')->on('tipo_visita')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ot');
    }
}
