<?php

namespace App\Http\Controllers\ControladorTecnicos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tecnico;
use App\Models\Usuario;
use App\Models\TecnicoServicio;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\Rules\Rut;
use Illuminate\Validation\Rule;

class TecnicosController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('search');

        // Obtener el número de resultados por página de la solicitud.
        // Si no se especifica, usar 10 como valor por defecto.
        $perPage = $request->input('perPage', 10);

        // Validar que $perPage sea un número entero positivo.
        // Si no es un número válido o es menor que 1, se usará 10 como fallback.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10; // Si filter_var devuelve false (no es entero positivo), usar 10

        // Opcional: Establecer un límite máximo para evitar cargas excesivas de datos
        // Por ejemplo, no permitir más de 200 registros por página
        $perPage = min($perPage, 200);

        $tecnicos = Tecnico::when($busqueda, function ($query) use ($busqueda) {
                if (is_numeric($busqueda)) {
                    $query->where('id', $busqueda);
                } else {
                    $query->where(function ($q) use ($busqueda) {
                        $q->where('nombre_tecnico', 'like', "%$busqueda%")
                          ->orWhere('rut_tecnico', 'like', "%$busqueda%")
                          ->orWhere('telefono_tecnico', 'like', "%$busqueda%")
                          ->orWhere('email_tecnico', 'like', "%$busqueda%")
                          ->orWhere('precio_hora_tecnico', 'like', "%$busqueda%");
                    });
                }
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage) // Usar $perPage dinámico
            ->appends($request->query()); // Usar $request->query() para mantener todos los parámetros (search, perPage)

        $data_todos_los_servicios = Servicio::all();
    
        return view('tecnicos.tecnicos', compact('tecnicos', 'data_todos_los_servicios'));
    }
    
    public function storetex(Request $request)
    {
        $request->validate([
            'tecnicox1' => 'required|exists:tecnico,id', // Asegura que el técnico exista
            'servicio' => 'required|exists:servicio,id', // Asegura que el servicio exista
        ]);

        TecnicoServicio::create([
            'cod_tecnico' => $request->tecnicox1,
            'cod_servicio' => $request->servicio,
        ]);

        return redirect()->back()->with('success', 'Técnico y servicio asignados correctamente.');
    }

    public function asignarServicios($id)
    {
        $tecnico = Tecnico::findOrFail($id);
        $servicios = servicio::all(); 
        $serviciosAsignados = $tecnico->servicios; // Obtener los servicios asignados
        $serviciosAsignadosId = $tecnico->servicios()->pluck('servicio.id'); // Obtener solo los IDs de los repuestos asignados
        return view('tecnicos.asignar', compact('tecnico', 'servicios', 'serviciosAsignados','serviciosAsignadosId'));
    }
    
    
    public function storeServicios(Request $request, $id)
    {
        // Validar que se hayan seleccionado repuestos
        $request->validate([
            'servicios' => 'required|array',
            'servicios.*' => 'exists:servicio,id', // Asegúrate de que los IDs de repuestos existan
        ]);
    
        // Encontrar el modelo por su ID
        $tecnico = Tecnico::findOrFail($id);
    
        // Asignar los repuestos al modelo
        $tecnico->servicios()->sync($request->servicios); // Asegúrate de que la relación esté definida en el modelo
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('tecnicos.index')->with('success', 'Repuestos asignados correctamente.');
    }
    
    public function buscar(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // <-- PERPAGE AQUÍ

        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10;
        $perPage = min($perPage, 200);

        $query = Cliente::query();

        if (is_numeric($search)) {
            $query->where('id', $search);
        } else {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_cliente', 'like', "%{$search}%")
                  ->orWhere('rut_cliente', 'like', "%{$search}%")
                  ->orWhere('email_cliente', 'like', "%{$search}%")
                  ->orWhere('telefono_cliente', 'like', "%{$search}%")
                  ->orWhere('web_cliente', 'like', "%{$search}%");
            });
        }

        $clientes = $query->orderBy('id', 'desc')
                        ->paginate($perPage)
                        ->appends($request->query()); // <-- appends($request->query())

        if ($clientes->isEmpty() && !empty($search)) {
            // Redirigir al index manteniendo perPage y search si no hay resultados
            return redirect()->route('clientes.index', $request->query())->with('error', 'No se encontraron clientes con ese término de búsqueda.');
        }

        return view('clientes.view_clientes', compact('clientes'));
    }

    public function create(Request $request)
    {
        $usuarios = Usuario::all();
        $servicios = Servicio::all();
        return view('tecnicos.agregar', compact('usuarios', 'servicios'));
    }
    
    public function nuevoMetodo()
    {
        
        $usuarios = Usuario::all();
        $servicios = Servicio::all();

        return view('tecnicos.agregar', compact('usuarios', 'servicios'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nombre_tecnico' => 'required|string|max:255',
            'rut_tecnico' => [
                'required',
                'string',
                'max:20',
                new Rut(),
                Rule::unique('tecnico')->where(function ($query) {
                    return $query->whereNull('deleted_at'); // Solo considera registros que no están eliminados
                }),
            ],
            'telefono_tecnico' => 'nullable|string|max:255',
            'email_tecnico' => 'nullable|string|email|max:255',
            'precio_hora_tecnico' => 'nullable|numeric',
            'cod_usuario' => 'nullable|exists:usuario,id',
            //'servicios' => 'nullable|array',
            //'servicios.*' => 'exists:servicio,id',
        ]);
    
        try {

            $tecnico = Tecnico::create([
                'nombre_tecnico' => $request->nombre_tecnico,
                'rut_tecnico' => $request->rut_tecnico,
                'telefono_tecnico' => $request->telefono_tecnico,
                'email_tecnico' => $request->email_tecnico,
                'precio_hora_tecnico' => $request->precio_hora_tecnico,
                'cod_usuario' => $request->cod_usuario,
            ]);
    
            // si el tecnico se creo bien
            if (!$tecnico || !$tecnico->nombre_tecnico) {
                Log::error('No se pudo crear el técnico o el nombre no se asignó correctamente.', ['tecnico' => $tecnico]);
                return redirect()->route('tecnicos.index')->with('error', 'No se pudo crear el técnico.');
            }
    
            // obtener los datos del tecnico recien creado usando 'nombre_tecnico'
            $tecnicoData = DB::table('tecnico')->where('nombre_tecnico', $tecnico->nombre_tecnico)->first();
            
            if (!$tecnicoData) {
                Log::error('No se encontró el técnico con el nombre proporcionado.', ['tecnico_nombre' => $tecnico->nombre_tecnico]);
                return redirect()->route('tecnicos.index')->with('error', 'Técnico no encontrado después de la creación.');
            }
            
            //if ($request->has('servicios') && count($request->servicios) > 0) {
            //    foreach ($request->servicios as $servicio_id) {
            //        TecnicoServicio::create([
            //            'cod_tecnico' => $tecnicoData->id,
            //           'cod_servicio' => $servicio_id,
            //        ]);
            //    }
            //}
           
            return redirect()->route('tecnicos.index')->with('success', 'Técnico creado exitosamente.');
    
        } catch (\Exception $e) {
            
            Log::error('Error al crear el técnico: ' . $e->getMessage());
            return redirect()->route('tecnicos.index')->with('error', 'Hubo un error al crear el técnico: ' . $e->getMessage());
        }
    }

    public function ver_avance_tecnicos($id)
    {

        $tecnico = Tecnico::with('usuario')->findOrFail($id);

        // obtener los roles asociados al tecnico, primero obtenemos el role_id de la tabla 'model_has_roles'
        $data_roldeltecnico = DB::table('model_has_roles')
            ->where('model_id', $tecnico->cod_usuario)  // filtrar por 'model_id' del usuario
            ->first();

        // si encuentra un role_id obtener el rol correspondiente
        if ($data_roldeltecnico) {
            $roles = Role::where('id', $data_roldeltecnico->role_id)->get();  // obtener rol con el role_id encontrado
        } else {
            $roles = [];  // si no se encuentra devolvemos un array vacio
        }
        
        $filtropatipo_servicio = TecnicoServicio::with('servicio')
        ->where('cod_tecnico', $tecnico->id)
        ->get();

        return view('tecnicos.detalle', compact('tecnico', 'roles', 'filtropatipo_servicio'));
    }
    
    public function edit_tecnico($id)
    {
        $tecnico = Tecnico::findOrFail($id);
        $usuarios = Usuario::all();
    
        return view('tecnicos.editar', compact('tecnico', 'usuarios'));
    }

    
    public function update_tecnico(Request $request, $id)
    {
        $tecnico = Tecnico::findOrFail($id);
    
        $validatedData = $request->validate([
            'nombre_tecnico' => 'required|string|max:255',
            'rut_tecnico' => [
                'required',
                'string',
                'max:20',
                new Rut(),
                Rule::unique('tecnico')->where(function ($query) {
                    return $query->whereNull('deleted_at'); // Solo considera registros que no están eliminados
                })->ignore($tecnico->id), // Ignora el RUT del técnico que se está editando
            ],
            'telefono_tecnico' => 'required|string|max:20',
            'email_tecnico' => 'required|email|max:255',
            'precio_hora_tecnico' => 'required|numeric',
            'cod_usuario' => 'required|exists:usuario,id',
        ]);
    
        $tecnico->update($validatedData);
    
        return redirect()->route('tecnicos.index')->with('success', 'Técnico actualizado correctamente.');
    }
    
    public function destroy($id)
    {
        try {
            // Buscar el técnico por su ID
            $tecnico = Tecnico::find($id);
            
            // Verificar si el técnico existe
            if (!$tecnico) {
                Log::error('No se encontró el técnico para eliminar.', ['tecnico_id' => $id]);
                return redirect()->route('tecnicos.index')->with('error', 'Técnico no encontrado.');
            }
    
            // Eliminar las relaciones de servicios del técnico
            TecnicoServicio::where('cod_tecnico', $tecnico->id)->delete();
    
            // Eliminar el técnico
            $tecnico->delete();
    
            // Si todo sale bien, redirigir con mensaje de éxito
            return redirect()->route('tecnicos.index')->with('success', 'Técnico eliminado exitosamente.');
    
        } catch (\Exception $e) {
            // En caso de error, registrar el error y redirigir con mensaje de error
            Log::error('Error al eliminar el técnico: ' . $e->getMessage());
            return redirect()->route('tecnicos.index')->with('error', 'Hubo un error al eliminar el técnico: ' . $e->getMessage());
        }
    }

}
