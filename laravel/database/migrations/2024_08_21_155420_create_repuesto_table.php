<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repuesto', function (Blueprint $table) {
            $table->id(); // `id` field with auto-increment
            $table->string('nombre_repuesto', 150);
            $table->string('descripcion_repuesto', 500);
            $table->string('part_number_repuesto', 255);

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
        Schema::dropIfExists('repuesto');
    }
}
