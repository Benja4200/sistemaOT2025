<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesorioDispositivoOtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesorio_dispositivo_ot', function (Blueprint $table) {
            $table->id();
            $table->text('cargador_acc')->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->text('cable_acc')->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->text('adaptador_acc')->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->text('bateria_acc')->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->text('pantalla_acc')->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->text('teclado_acc')->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->text('drum_acc')->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->text('toner_acc')->charset('utf8mb4')->collation('utf8mb4_spanish_ci');
            $table->unsignedBigInteger('cod_dispositivo_ot');
            $table->timestamps(); // Agrega created_at y updated_at
            $table->softDeletes(); // Agrega deleted_at

            // Índices
            $table->foreign('cod_dispositivo_ot')
                ->references('id')
                ->on('dispositivo_ot')
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
        Schema::dropIfExists('accesorio_dispositivo_ot');
    }
}
