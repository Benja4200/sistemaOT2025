<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnicoServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tecnico_servicio', function (Blueprint $table) {
            $table->id(); // `id` field with auto-increment
            $table->unsignedBigInteger('cod_servicio');
            $table->unsignedBigInteger('cod_tecnico');

            $table->timestamps(); // Includes 'created_at' and 'updated_at'
            $table->softDeletes(); // Adds 'deleted_at' column

            // Foreign key constraints
            $table->foreign('cod_servicio')
                ->references('id')->on('servicio')
                ->onDelete('cascade'); // Use 'cascade' to delete related records in tecnico_servicio if servicio is deleted

            $table->foreign('cod_tecnico')
                ->references('id')->on('tecnico')
                ->onDelete('cascade'); // Use 'cascade' to delete related records in tecnico_servicio if tecnico is deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tecnico_servicio');
    }
}
