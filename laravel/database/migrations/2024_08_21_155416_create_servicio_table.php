<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicio', function (Blueprint $table) {
            $table->id(); // `id` field with auto-increment
            $table->string('nombre_servicio', 100);
            $table->unsignedBigInteger('cod_tipo_servicio');
            $table->unsignedBigInteger('cod_sublinea');

            $table->timestamps(); // Includes 'created_at' and 'updated_at'
            $table->softDeletes(); // Adds 'deleted_at' column

            // Foreign key constraints
            $table->foreign('cod_tipo_servicio')
                ->references('id')->on('tipo_servicio')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('cod_sublinea')
                ->references('id')->on('sublinea')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicio');
    }
}
