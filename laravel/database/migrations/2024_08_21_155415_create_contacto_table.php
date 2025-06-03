<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_contacto', 50);
            $table->string('telefono_contacto', 30);
            $table->string('departamento_contacto', 50);
            $table->string('cargo_contacto', 50);
            $table->string('email_contacto', 50);
            $table->unsignedBigInteger('cod_sucursal')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cod_sucursal')->references('id')->on('sucursal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacto');
    }
}
