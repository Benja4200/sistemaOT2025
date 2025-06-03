<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id(); // `id` field with auto-increment
            $table->string('nombre_usuario', 40); // VARCHAR(40)
            $table->string('password_usuario', 50); // VARCHAR(50)
            $table->string('rol_usuario', 255); // VARCHAR(255)
            $table->string('email_usuario', 100); // VARCHAR(100)
            $table->timestamp('email_verified_at')->nullable(); // TIMESTAMP NULL DEFAULT NULL
            $table->timestamps(); // Includes 'created_at' and 'updated_at'
            $table->softDeletes(); // Adds 'deleted_at' column

            $table->primary('id'); // Primary key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
