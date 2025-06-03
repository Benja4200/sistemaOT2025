<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_extra', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_actividad', 2000)->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->integer('horas_actividad');
            $table->unsignedBigInteger('cod_ot');
            $table->timestamps(); // Agrega created_at y updated_at
            $table->softDeletes(); // Agrega deleted_at

            // Índices
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
        Schema::dropIfExists('actividad_extra');
    }
}
