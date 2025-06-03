<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Ot;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico es inválido.',
            'password.required' => 'El campo contraseña es obligatorio.',
        ]);
    
        // filtramo el usuario por correo
        $user = Usuario::where('email_usuario', $credentials['email'])->first();
    
        // si el usuario existe y la contra es correcta
        if ($user && password_verify($request->input('password'), $user->password_usuario)) {
            
            // Iniciar sesión
            Auth::login($user);
    
            // si los roles del usuario existen
            if ($user->hasAnyRole(['Administrador', 'Administrativo', 'Ejecutivo'])) {
                
                // redirigir a la pagina principal si el usuario es Administrador
                return redirect()->route('home');
                
            } elseif ($user->hasRole('Tecnicos')) {
                
                return redirect()->route('misOrdenes');
                
                // redirigir a las ordenes del tecnico si el usuario tiene ese rol (puede que no pase de aqui abra que eliminar)
                $datosUserLogeado = Usuario::where('id', $user->id)->first();
    
                $datosTecnicoxUsuario = Tecnico::where('cod_usuario', $datosUserLogeado->id)->first();
    
                $datosMisOrdenes = Ot::where('cod_tecnico_encargado', $datosTecnicoxUsuario->id)->paginate(10);
    
                return view('ordenespropiasxtecnico.misordenes', compact('datosUserLogeado', 'datosMisOrdenes'));

            } else {
                // Si el usuario no tiene un rol valido, redirigir al login con el mensaje de error
                return Redirect::back()->withErrors([
                    'email' => 'El usuario no tiene un rol válido.',
                    'password' => 'El usuario no tiene un rol válido.',
                ])->with('error_global', 'El usuario no tiene un rol válido.');
            }
        } else {
            // si las credenciales son incorrectas, redirigir de nuevo al formulario de login con un error
            return Redirect::back()->withErrors([
                'email' => 'Email inválido.',
                'password' => 'password inválido.',
            ])->with('error_global', 'Alguna credencial proporcionada son incorrectas.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
