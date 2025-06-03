<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_modelo', 100);
            $table->string('desc_corta_modelo', 300);
            $table->string('desc_larga_modelo', 500);
            $table->string('part_number_modelo', 50);
            $table->unsignedBigInteger('cod_marca');
            $table->unsignedBigInteger('cod_sublinea')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cod_marca')->references('id')->on('marca')->onDelete('cascade');
            $table->foreign('cod_sublinea')->references('id')->on('sublinea')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modelo');
    }
}
