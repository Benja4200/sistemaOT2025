<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrioridadOtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prioridad_ot', function (Blueprint $table) {
            $table->id(); // `id` field with auto-increment
            $table->string('descripcion_prioridad_ot', 255);

            $table->timestamps(); // Includes 'created_at' and 'updated_at'
            $table->softDeletes(); // Adds 'deleted_at' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prioridad_ot');
    }
}
