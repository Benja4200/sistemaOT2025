<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('convenios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('cliente')->onDelete('cascade'); // Clave foránea para clientes
            $table->foreignId('id_branch')->constrained()->onDelete('cascade'); // Clave foránea para sucursales
            $table->integer('visitas_presenciales');
            $table->integer('visitas_emergencia');
            $table->integer('soporte_remoto');
            $table->integer('horas_tecnicas');
            $table->string('estado');
            $table->timestamps(); // Incluye created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convenios');
    }
};
