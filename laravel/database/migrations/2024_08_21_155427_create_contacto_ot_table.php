<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactoOtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacto_ot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cod_contacto');
            $table->unsignedBigInteger('cod_ot');
            $table->timestamps();
            $table->softDeletes(); // Agrega la columna deleted_at

            $table->foreign('cod_contacto')->references('id')->on('contacto')->onDelete('cascade');
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
        Schema::dropIfExists('contacto_ot');
    }
}
