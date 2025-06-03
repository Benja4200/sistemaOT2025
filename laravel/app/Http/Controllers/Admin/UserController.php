<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener el número de resultados por página de la solicitud, con un valor por defecto (ej. 10)
        $perPage = $request->input('perPage', 10);

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10; // Si filter_var devuelve false, usar 10
        $perPage = min($perPage, 200); // Limitar a un máximo de 200 registros

        // Obtener el término de búsqueda de la solicitud (puede venir de una redirección)
        $search = $request->input('search');

        // Construir la consulta base para los usuarios
        $query = Usuario::with('roles')->orderBy('id', 'desc');

        // Aplicar el filtro de búsqueda si existe
        if (!empty($search)) {
            $query->where('nombre_usuario', 'like', "%{$search}%")
                  ->orWhere('email_usuario', 'like', "%{$search}%")
                  ->orWhereHas('roles', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        // Obtener los usuarios paginados, incluyendo los parámetros de búsqueda y perPage
        $users = $query->paginate($perPage)->appends($request->query());

        // Si no se encontraron usuarios para la búsqueda actual (cuando se viene de 'buscar')
        // y se tiene un término de búsqueda, se añade un mensaje flash.
        if ($users->isEmpty() && !empty($search)) {
            session()->flash('error', 'No se encontraron usuarios con el término: "' . $search . '".');
        }

        $rolesx = Role::all(); // Obtener todos los roles para la tabla (no solo para búsqueda)

        return view('usuarios.usuarios', compact('users', 'rolesx', 'search'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('usuarios.crear', compact('roles'));
    }

    public function store(Request $request)
    {
        // validaciones de request
        $request->validate([
            'nombre_usuario' => 'required|string|max:255',
            'email_usuario' => 'required|string|email|max:255|unique:usuario,email_usuario',
            'password_usuario' => 'required|string|min:8|confirmed',
            'rol_usuario' => 'required|exists:roles,id',
        ]);

        // Crear el usuario
        $user = Usuario::create([
            'nombre_usuario' => $request->nombre_usuario,
            'email_usuario' => $request->email_usuario,
            'password_usuario' => bcrypt($request->password_usuario),
        ]);

        // asegurar de que el ID se genero
        if (!$user->id) {
            return redirect()->back()->withErrors(['error' => 'No se pudo crear el usuario.']);
        }

        // Obtener el rol seleccionado por el usuario
        $role = Role::find($request->rol_usuario);

        if ($role) {

            DB::table('model_has_roles')->insert([
                'model_id' => $user->id,           // ID del usuario
                'role_id' => $role->id,            // ID del rol
                'model_type' => get_class($user),  // tipo de modelo, que es el nombre completo de la clase
            ]);

        } else {
            return redirect()->back()->withErrors(['rol_usuario' => 'Rol no valido.']);
        }

        return redirect()->route('usuarios.index')->with('info', 'Usuario creado con exito');
    }




    public function edit($id)
    {

        $user = Usuario::findOrFail($id);

        // Cargar los roles y devolver la vista de editar
        $roles = Role::all();
        return view('usuarios.editar', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre_usuario' => 'required|string|max:255',
            'email_usuario' => 'required|email|max:255|unique:usuario,email_usuario,' . $id,
            'password' => 'nullable|confirmed|min:8', // Si la contraseña es proporcionada, debe ser confirmada
            'roles' => 'required|array', // Los roles deben ser un array
            'roles.*' => 'exists:roles,id', // Verifica que los roles existan en la tabla roles
        ]);

        // Buscar el usuario
        $user = Usuario::findOrFail($id);

        // Actualizar los campos básicos
        $user->nombre_usuario = $request->input('nombre_usuario');
        $user->email_usuario = $request->input('email_usuario');

        // Si se proporciona una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $user->password_usuario = Hash::make($request->input('password'));
        }

        // Guardar los cambios en el usuario
        $user->save();

        // Eliminar los roles antiguos del usuario (si es necesario)
        DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->delete();

        // Asignar los roles seleccionados manualmente
        foreach ($request->input('roles') as $roleId) {
            DB::table('model_has_roles')->insert([
                'model_id' => $user->id,           // ID del usuario
                'role_id' => $roleId,               // ID del rol seleccionado
                'model_type' => get_class($user),   // Tipo de modelo (nombre completo de la clase Usuario)
            ]);
        }

        // Redirigir a la página de usuarios con un mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function buscar(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Mantener el perPage de la solicitud si viene.

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10;
        $perPage = min($perPage, 200);

        // Si el término de búsqueda está vacío, redirigir al index sin el parámetro 'search'
        if (empty($search)) {
            return redirect()->route('usuarios.index', ['perPage' => $perPage]);
        }

        $users = Usuario::with('roles')
            ->where('nombre_usuario', 'like', "%{$search}%")
            ->orWhere('email_usuario', 'like', "%{$search}%")
            ->orWhereHas('roles', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'perPage' => $perPage]); // Asegurar que los parámetros se mantengan

        // Si no se encontraron usuarios para el término de búsqueda, redirigir al index
        // y añadir el término de búsqueda al index para que se muestre en el input y el mensaje.
        if ($users->isEmpty()) {
            return redirect()->route('usuarios.index', ['perPage' => $perPage, 'search' => $search])
                             ->with('error', 'No se encontraron usuarios con el término: "' . $search . '".');
        }

        $rolesx = Role::all(); // Asegúrate de pasar rolesx también en la vista de búsqueda

        return view('usuarios.usuarios', compact('users', 'rolesx', 'search'));
    }

    public function destroy($id)
    {
        // Buscar al usuario por su ID
        $user = Usuario::findOrFail($id);

        // Eliminar roles asignados al usuario (si es necesario)
        DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->delete();

        // Eliminar al usuario
        $user->delete();

        // Redirigir a la lista de usuarios con un mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
    
    public function perfil()
    {
         $user = auth()->user()->load('roles');
         
         // Si necesitas más datos relacionados
         // $user->load('otraRelacion', 'masRelaciones');
         
         return view('perfil.index', compact('user'));
    }
     // Mostrar formulario de edición de perfil
     public function editPerfil()
     {
         $user = auth()->user();
         return view('perfil.edit', compact('user'));
     }
     
     // Actualizar perfil
     public function updatePerfil(Request $request)
     {
         $user = auth()->user();
         
         $request->validate([
             'nombre_usuario' => 'required|string|max:255',
             'email_usuario' => 'required|email|max:255|unique:usuario,email_usuario,'.$user->id,
             'password' => 'nullable|confirmed|min:8',
         ]);
     
         $user->nombre_usuario = $request->nombre_usuario;
         $user->email_usuario = $request->email_usuario;
         
         if ($request->password) {
             $user->password_usuario = Hash::make($request->password);
         }
         
         $user->save();
     
         return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente');
     }
     
    public function createSignature($userId)
    {
        $user = Usuario::findOrFail($userId); // Asegúrate de que el usuario existe
        return view('perfil.create_signature', compact('user'));
    }

    public function storeSignature(Request $request, $userId)
    {
        //dd($request);
        $request->validate([
            'signature' => 'required|string',
        ]);

        // Obtener el usuario autenticado
        $user = Usuario::findOrFail($userId);

        // Verificar si el usuario es el mismo que el que está intentando crear la firma
        if ($user->id !== (int)$userId) {
            return redirect()->route('perfil')->with('error', 'No tienes permiso para modificar esta firma.');
        }

        // Verificar si la firma ya existe
        if (!is_null($user->firma)) {
            return redirect()->route('perfil.edit_signature', $userId)->with('info', 'Ya tienes una firma. Puedes editarla.');
        }

        // Almacenar la firma en el campo 'firma' del usuario
        $user->firma = $request->signature;
        $user->save();

        return redirect()->route('perfil')->with('success', 'Firma guardada exitosamente.');
    }
    
    public function editSignature($userId)
    {
        $user = Usuario::findOrFail($userId); // Obtiene el usuario por ID
        return view('perfil.edit_signature', compact('user')); // Pasa el usuario a la vista
    }
    
    public function updateSignature(Request $request, $userId)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);
    
        $user = Usuario::findOrFail($userId);
        $user->firma = $request->signature; // Actualiza la firma
        $user->save(); // Guarda los cambios
    
        return redirect()->route('perfil')->with('success', 'Firma actualizada correctamente.');
    }
}
