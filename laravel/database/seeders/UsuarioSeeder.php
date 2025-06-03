<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        DB::table('usuario')->insert([
            'id' => 101, // Cambia el ID según sea necesario
            'nombre_usuario' => 'admin',
            'password_usuario' => Hash::make('password'), // Utiliza Bcrypt para encriptar la contraseña
            'rol_usuario' => 'ADMINISTRADOR',
            'email_usuario' => 'password@passwordl.cl',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
