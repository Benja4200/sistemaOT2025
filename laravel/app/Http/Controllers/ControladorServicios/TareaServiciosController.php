<?php

namespace App\Http\Controllers\ControladorServicios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Subtarea;
use App\Models\Subcategoria;

class TareaServiciosController extends Controller
{
    public function index(Request $request) // Añadido Request $request
    {
        // 1. Obtener el número de resultados por página de la solicitud, con un valor por defecto (ej. 6)
        $perPage = $request->input('perPage', 6);

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6; // Si filter_var devuelve false, usar 6
        $perPage = min($perPage, 200); // Limitar a un máximo de 200 registros

        // Obtener el término de búsqueda de la solicitud (puede venir de una redirección)
        $search = $request->input('search');

        // Iniciar la consulta para las tareas
        $query = Tarea::orderBy('id', 'desc');

        // Aplicar el filtro de búsqueda si existe
        if (!empty($search)) {
            $query->where('nombre_tarea', 'like', "%{$search}%")
                  ->orWhereHas('subcategoria', function ($query) use ($search) {
                      $query->where('nombre_subcategoria', 'like', "%{$search}%");
                  });
        }

        // Obtener las tareas paginadas, incluyendo los parámetros de búsqueda y perPage
        $tareas = $query->paginate($perPage)->appends($request->query());

        // Si no se encontraron tareas para la búsqueda actual (cuando se viene de 'buscar_tarea')
        // y se tiene un término de búsqueda, se añade un mensaje flash.
        if ($tareas->isEmpty() && !empty($search)) {
            session()->flash('error', 'No se encontraron tareas con el término: "' . $search . '".');
        }

        return view('tareas.tareas', compact('tareas', 'search'));
    }

    public function redirec_agre_tarea()
    {
        $servicios = Servicio::all();
        $subcategorias = Subcategoria::whereHas('categoria', function($query) {
            $query->where('nombre_categoria', 'SERVICIO'); // Asegúrate de que este sea el nombre correcto de la categoría
        })->get();
        return view('tareas.agregar', compact('servicios','subcategorias'));
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre_tarea' => 'required|string|max:100',
            'cod_subcategoria' => 'required|exists:subcategoria,id',
            'requiere_obs' => 'required|string|size:2',
            //'tiempo_tarea' => 'required|integer',
            //'cod_servicio' => 'required|exists:servicio,id',
            //'descripcion_subtarea' => 'required|string|max:100'
        ]);
    
        // Verificar si ya existe una tarea con el mismo nombre y la misma subcategoría
        $existingTask = Tarea::where('nombre_tarea', $request->nombre_tarea)
            ->where('cod_subcategoria', $request->cod_subcategoria) // Asegúrate de que este campo esté en la tabla tarea
            ->first();
    
        if ($existingTask) {
            return redirect()->back()->withErrors(['nombre_tarea' => 'Ya existe una tarea con este nombre en la subcategoría seleccionada.']);
        }
    
        // Crear la tarea
        $tareaxs = Tarea::create($request->all());
    
        // Si necesitas crear una subtarea, asegúrate de que la lógica esté aquí
        if ($request->has('descripcion_subtarea')) {
            $creadordesubtarea = Subtarea::create([
                'descripcion' => $request->descripcion_subtarea,
                'cod_tarea' => $tareaxs->id, // Usamos el ID de la tarea recién creada
            ]);
        }
    
        //return redirect()->route('tareas.index')->with('success', 'Tarea y subtarea creadas exitosamente.');
        return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente.');
    }

    public function ver_avance_tarea($id)
    {
        $tarea = Tarea::findOrFail($id);
        return view('tareas.detalle', compact('tarea'));
    }

    public function edit($id)
    {

        $tarea = Tarea::findOrFail($id);
        $servicios = Servicio::all();
        $subcategorias = Subcategoria::whereHas('categoria', function($query) {
            $query->where('nombre_categoria', 'SERVICIO'); // Asegúrate de que este sea el nombre correcto de la categoría
        })->get();
        return view('tareas.editar', compact('tarea', 'servicios','subcategorias'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'nombre_tarea' => 'required|string|max:255',
            //'tiempo_tarea' => 'required|integer',
            //'cod_servicio' => 'required|exists:servicio,id',
            'cod_subcategoria' => 'required|exists:subcategoria,id',
            'requiere_obs' => 'required|string|size:2',
        ]);
        
        
        $existingTask = Tarea::where('nombre_tarea', $request->nombre_tarea)
        ->where('cod_subcategoria', $request->cod_subcategoria) // Asegúrate de que este campo esté en la tabla tarea
        ->where('id', '!=', $id) // Excluir la tarea que se está actualizando
        ->first();

        if ($existingTask) {
            return redirect()->back()->withErrors(['nombre_tarea' => 'Ya existe una tarea con este nombre en la subcategoría seleccionada.']);
        }


        $tarea = Tarea::findOrFail($id);

        $tarea->update($request->all());

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada exitosamente.');
    }


    public function destroy($id)
    {

        $tarea = Tarea::findOrFail($id);
        $tarea->delete();

        return redirect()->route('tareas.index');
        //->with('success', 'Tarea eliminada exitosamente.');
    }

    public function buscar_tarea(Request $request)
{
    $search = $request->input('search');
    $perPage = $request->input('perPage', 6);

    // Validar que $perPage sea un número entero positivo.
    $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $perPage = $perPage ?: 6;
    $perPage = min($perPage, 200);

    // Si el término de búsqueda está vacío, redirigir al index con un mensaje de error y el perPage
    if (empty($search)) {
        return redirect()->route('tareas.index', ['perPage' => $perPage])
                         ->with('error', 'Por favor ingrese un término de búsqueda para tareas.');
    }

    $query = Tarea::with(['subcategoria']);

    // Verificar si la búsqueda es un número (por ejemplo, ID)
    if (is_numeric($search)) {
        $query->where('id', $search);
    } else {
        $query->where('nombre_tarea', 'like', "%{$search}%")
              ->orWhereHas('subcategoria', function ($subcategoriaQuery) use ($search) {
                  $subcategoriaQuery->where('nombre_subcategoria', 'like', "%{$search}%");
              });
    }

    // Realizar la consulta y paginar los resultados
    $tareas = $query->orderBy('id', 'desc')
                    ->paginate($perPage)
                    ->appends(['search' => $search, 'perPage' => $perPage]);

    // *** CAMBIO CLAVE AQUÍ: NO REDIRIGIR SI NO HAY RESULTADOS ***
    // En su lugar, la vista recibirá las $tareas vacías y un mensaje de error.
    return view('tareas.tareas', compact('tareas', 'search'))
        ->with('error', $tareas->isEmpty() ? 'No se encontraron tareas con el término: "' . $search . '".' : null);
}


}
