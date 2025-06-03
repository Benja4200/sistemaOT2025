<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tecnico', function (Blueprint $table) {
            $table->id(); // `id` field with auto-increment
            $table->string('nombre_tecnico', 50);
            $table->string('rut_tecnico', 15);
            $table->string('telefono_tecnico', 15);
            $table->string('email_tecnico', 50);
            $table->integer('precio_hora_tecnico');
            $table->unsignedBigInteger('cod_usuario')->nullable(); // Allow NULL for optional foreign key

            $table->timestamps(); // Includes 'created_at' and 'updated_at'
            $table->softDeletes(); // Adds 'deleted_at' column

            // Foreign key constraints
            $table->foreign('cod_usuario')
                ->references('id')->on('usuario')
                ->onDelete('set null'); // Use 'set null' to maintain integrity if user is deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tecnico');
    }
}
