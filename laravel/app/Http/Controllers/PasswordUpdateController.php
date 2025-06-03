<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateController extends Controller
{
    public function index()
    {
        // Nueva contraseña que se asignará a todos los usuarios
        $newPassword = 'Dssc3066';

        // Consultar todas las filas de la tabla usuario
        $usuarios = DB::table('usuario')->select('id')->get();

        // Verificar si hay usuarios
        if ($usuarios->isNotEmpty()) {
            foreach ($usuarios as $usuario) {
                $id = $usuario->id;

                // Encriptar la nueva contraseña con Bcrypt
                $hashedPassword = Hash::make($newPassword);

                // Actualizar la contraseña en la base de datos
                DB::table('usuario')->where('id', $id)->update([
                    'password_usuario' => $hashedPassword // Almacena la contraseña en formato Bcrypt
                ]);

                echo "Contraseña del usuario con ID $id actualizada con éxito.<br>";
            }
        } else {
            echo "No se encontraron usuarios.";
        }
    }
}
