<?php

namespace App\Http\Controllers\ControladorRoles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el número de resultados por página de la solicitud
        $perPage = $request->input('perPage', 10); // Valor por defecto 10

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10; // Si filter_var devuelve false, usar 10
        $perPage = min($perPage, 200); // Limitar a un máximo de 200 registros

        // Obtener el término de búsqueda de la solicitud (puede venir de una redirección)
        $search = $request->input('search');

        // Construir la consulta base para los roles
        $query = Role::with('permissions')->orderBy('id', 'desc');

        // Aplicar el filtro de búsqueda si existe
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Obtener los roles paginados, incluyendo los parámetros de búsqueda y perPage
        $roles = $query->paginate($perPage)->appends($request->query());

        // Si no se encontraron roles para la búsqueda actual (cuando se viene de 'buscar')
        // y se tiene un término de búsqueda, se añade un mensaje flash.
        if ($roles->isEmpty() && !empty($search)) {
            session()->flash('error', 'No se encontraron roles con el término: "' . $search . '".');
        }

        return view('roles.roles', compact('roles', 'search'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.crear', compact('permissions'));
    }

    public function store(Request $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.index')->with('info', 'Rol creado con éxito');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.editar', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.index')->with('info', 'Permisos del rol asignados correctamente');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('info', 'Rol eliminado con éxito');
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
            return redirect()->route('roles.index', ['perPage' => $perPage]);
        }

        $roles = Role::where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orderBy('id', 'desc') // Agregado para consistencia con index
            ->paginate($perPage)
            ->appends(['search' => $search, 'perPage' => $perPage]); // Asegurar que los parámetros se mantengan

        // Si no se encontraron roles para el término de búsqueda, redirigir al index
        // y añadir el término de búsqueda al index para que se muestre en el input y el mensaje.
        if ($roles->isEmpty()) {
            return redirect()->route('roles.index', ['perPage' => $perPage, 'search' => $search])
                             ->with('error', 'No se encontraron roles con el término: "' . $search . '".');
        }

        return view('roles.roles', compact('roles', 'search'));
    }
}
