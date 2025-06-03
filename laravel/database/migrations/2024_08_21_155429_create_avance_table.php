<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avance', function (Blueprint $table) {
            $table->id();
            $table->string('comentario_avance', 5000);
            $table->timestamp('fecha_avance')->nullable();
            $table->integer('tiempo_avance');
            $table->unsignedBigInteger('cod_ot');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cod_ot')->references('numero_ot')->on('ot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avance');
    }
}
