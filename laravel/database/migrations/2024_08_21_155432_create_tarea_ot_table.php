<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareaOtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarea_ot', function (Blueprint $table) {
            $table->id(); // `id` field with auto-increment
            $table->unsignedBigInteger('cod_tarea');
            $table->unsignedBigInteger('cod_ot');

            $table->timestamps(); // Includes 'created_at' and 'updated_at'
            $table->softDeletes(); // Adds 'deleted_at' column

            // Foreign key constraints
            $table->foreign('cod_tarea')
                ->references('id')->on('tarea')
                ->onDelete('cascade');

            $table->foreign('cod_ot')
                ->references('numero_ot')->on('ot')
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
        Schema::dropIfExists('tarea_ot');
    }
}
