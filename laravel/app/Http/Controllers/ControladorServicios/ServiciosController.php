<?php

namespace App\Http\Controllers\ControladorServicios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Sublinea;
use App\Models\TipoServicio;
use App\Models\Linea;
use App\Models\Tarea;
use Illuminate\Support\Facades\DB;
class ServiciosController extends Controller
{
    public function index(Request $request) // <-- Add Request $request here
    {
        // Obtener el número de resultados por página de la solicitud
        // Si no se especifica, usar 6 como valor por defecto.
        $perPage = $request->input('perPage', 6);

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6; // Si filter_var devuelve false, usar 6

        // Opcional: Establecer un límite máximo para evitar cargas excesivas de datos
        $perPage = min($perPage, 200);

        // Construir la consulta base para los servicios
        // Load relationships eagerly for better performance in the view
        $query = Servicio::orderBy('id', 'desc')->with(['tipoServicio', 'sublinea.linea.subcategoria.categoria']);

        // Aplicar lógica de búsqueda si el campo 'search' está presente en la solicitud
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', $search);
                } else {
                    $q->where('nombre_servicio', 'like', "%$search%")
                      ->orWhereHas('tipoServicio', function ($subQuery) use ($search) {
                          $subQuery->where('descripcion_tipo_servicio', 'like', "%$search%");
                      })
                      ->orWhereHas('sublinea', function ($subQuery) use ($search) {
                          $subQuery->where('nombre_sublinea', 'like', "%$search%");
                      });
                }
            });
        }

        // Paginación final de los resultados, manteniendo los filtros
        $servicios = $query->paginate($perPage)->appends($request->except('page'));

        return view('servicios.servicios', compact('servicios'));
    }

    public function create()
    {
        // Obtener la categoría "SERVICIO"
        $categoriaServicio = Categoria::where('nombre_categoria', 'SERVICIO')->first();
        
        // Verificar si la categoría "SERVICIO" existe
        if ($categoriaServicio) {
            // Obtener solo las subcategorías que pertenecen a la categoría "SERVICIO"
            $subcategorias = Subcategoria::where('cod_categoria', $categoriaServicio->id)->get();
        } else {
            // Si no existe, obtener todas las subcategorías del sistema
            $subcategorias = Subcategoria::all();
        }
    
        // Obtener todos los tipos de servicio y sublíneas
        $tiposServicio = TipoServicio::all();
        $sublineas = Sublinea::all();
        $lineas = Linea::all();
        $categorias = Categoria::where('nombre_categoria', '!=', 'SERVICIO')->get(); // Excluir la categoría "SERVICIO"
    
        return view('servicios.agregar', compact('tiposServicio', 'sublineas', 'categorias', 'subcategorias', 'lineas'));
    }


    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'nombre_servicio' => 'required|string|max:100',
            'cod_tipo_servicio' => 'required|integer',
            'cod_sublinea' => 'required|integer',
            'cod_categoria' => 'required_if:cod_tipo_servicio,2|integer', // Requerir solo si el tipo es 2
        ]);
    
        // Crear el nuevo servicio
        $servicio = Servicio::create([
            'nombre_servicio' => $request->nombre_servicio,
            'cod_tipo_servicio' => $request->cod_tipo_servicio,
            'cod_sublinea' => $request->cod_sublinea,
        ]);
    
        // Sincronizar categorías solo si el tipo de servicio es 2
        if ($request->cod_tipo_servicio == 2) {
            $servicio->categoriasEquipos()->attach([$request->cod_categoria]);
        }
    
        // Redirigir a la lista de servicios con un mensaje de éxito
        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente.');
    }
    public function buscar(Request $request) // Añadido Request $request
    {
        // Obtener el término de búsqueda y el número de elementos por página
        $search = $request->input('search');
        $perPage = $request->input('perPage', 6); // Usar el mismo valor por defecto que en index

        // Asegurar que $perPage sea un número entero positivo
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6; // Fallback a 6 si no es válido
    
        // Iniciar la consulta
        $query = Servicio::query(); // Iniciar la consulta del modelo Servicio

        // Aplicar el filtro de búsqueda
        if (is_numeric($search)) {
            // Si es un número, buscar por el ID del servicio
            $query->where('id', $search);
        } else {
            // Si es texto, buscar por nombre de servicio, descripción de tipo de servicio o nombre de sublínea
            $query->where('nombre_servicio', 'like', "%{$search}%")
                  ->orWhereHas('tipoServicio', function ($q) use ($search) {
                      $q->where('descripcion_tipo_servicio', 'like', "%{$search}%");
                  })
                  ->orWhereHas('sublinea', function ($q) use ($search) {
                      $q->where('nombre_sublinea', 'like', "%{$search}%");
                  });
        }
    
        // Realizar la consulta y paginar los resultados, manteniendo los parámetros de la URL
        $servicios = $query->orderBy('id', 'desc')->paginate($perPage)->appends($request->query());
    
        // Retornar la vista con los servicios paginados
        return view('servicios.servicios', compact('servicios'));
    }

    
    public function edit($id)
    {
        // Obtener la categoría "SERVICIO"
        $categoriaServicio = Categoria::where('nombre_categoria', 'SERVICIO')->first();
        
        // Buscar el servicio por ID
        $servicio = Servicio::findOrFail($id);
        
        // Inicializar variables
        $sublineas = [];
        $lineas = [];
        $subcategorias = [];
        $subcategoria = null;
        $linea = null;
        $sublinea = null;
    
        // Verificar si el servicio tiene una sublínea
        if ($servicio->cod_sublinea) {
            // Obtener la sublínea del servicio
            $sublinea = Sublinea::find($servicio->cod_sublinea);
        }
    
        // Verificar si la sublínea existe
        if ($sublinea) {
            // Obtener la línea asociada a la sublínea
            $linea = Linea::find($sublinea->cod_linea);
        }
    
        // Verificar si la línea existe
        if ($linea) {
            // Obtener la subcategoría asociada a la línea
            $subcategoria = Subcategoria::find($linea->cod_subcategoria);
        }
    
        // Obtener solo las subcategorías que pertenecen a la categoría "SERVICIO"
        $subcategorias = Subcategoria::where('cod_categoria', $categoriaServicio->id)->get();
    
        // Si no hay sublínea, línea o subcategoría, obtener todas las opciones
        if (is_null($sublinea) || is_null($linea) || is_null($subcategoria)) {
            $lineas = Linea::all();
            $sublineas = Sublinea::all();
        } else {
            // Si todas las relaciones existen, obtener las líneas y sublíneas asociadas
            $lineas = Linea::where('cod_subcategoria', $subcategoria->id)->get();
            $sublineas = Sublinea::where('cod_linea', $linea->id)->get();
        }
    
        // Obtener todos los tipos de servicio
        $tiposServicio = TipoServicio::all();
        
        // Obtener todas las categorías (si es necesario)
        $categorias = Categoria::where('nombre_categoria', '!=', 'SERVICIO')->get();
        
        return view('servicios.editar', compact('servicio', 'tiposServicio', 'sublineas', 'categorias', 'subcategorias', 'lineas', 'subcategoria', 'linea'));
    }
    
    public function update(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'nombre_servicio' => 'required|string|max:255',
            'cod_tipo_servicio' => 'required|integer',
            'cod_sublinea' => 'required|integer',
            'cod_categoria' => 'required_if:cod_tipo_servicio,2|integer', // Requerir solo si el tipo es 2
        ]);
    
        // Encontrar el servicio y actualizarlo
        $servicio = Servicio::findOrFail($id);
        $servicio->update([
            'nombre_servicio' => $request->nombre_servicio,
            'cod_tipo_servicio' => $request->cod_tipo_servicio,
            'cod_sublinea' => $request->cod_sublinea,
        ]);
    
        // Sincronizar categorías solo si el tipo de servicio es 2
        if ($request->cod_tipo_servicio == 2) {
            $servicio->categoriasEquipos()->sync([$request->cod_categoria]);
        } else {
            // Si el tipo de servicio no es 2, eliminar las categorías asociadas
            $servicio->categoriasEquipos()->detach();
        }
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Buscar el servicio por ID y eliminarlo
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        // Redirigir con un mensaje de éxito
        return back();
        //->with('success', 'Servicio eliminado exitosamente.');
    }
    public function show($id)
    {
        // Buscar el servicio por ID
        $servicio = Servicio::findOrFail($id);
        return view('servicios.detalle', compact('servicio'));
    }
    
    public function mostrarAsignarTareas($servicioId)
    {
        $servicio = Servicio::findOrFail($servicioId);
        
        // Inicializar el ID de la subcategoría como null
        $subcategoriaId = null;
    
        // Verificar si la sublínea existe
        if ($servicio->sublinea) {
            // Verificar si la línea existe
            if ($servicio->sublinea->linea) {
                // Verificar si la subcategoría existe
                if ($servicio->sublinea->linea->subcategoria) {
                    $subcategoriaId = $servicio->sublinea->linea->subcategoria->id;
                }
            }
        }
    
        // Si no se encontró una subcategoría, puedes manejarlo aquí (opcional)
        if (is_null($subcategoriaId)) {
            // Manejo si no hay subcategoría
            // Por ejemplo, podrías redirigir o mostrar un mensaje
            return redirect()->back()->with('error', 'No se encontró la subcategoría asociada al servicio.');
        }
    
        // Filtrar las tareas que tienen la misma subcategoría
        $tareas = Tarea::where('cod_subcategoria', $subcategoriaId)->get();
    
        // Obtener las tareas ya asignadas al servicio
        $tareasAsignadas = $servicio->tareasServicio()->pluck('tarea.id')->toArray();
    
        // Filtrar las tareas para que no incluyan las ya asignadas
        $tareas = $tareas->whereNotIn('id', $tareasAsignadas);
    
        return view('servicios.asignarTareas', compact('servicio', 'tareas'));
    }
    
    public function asignarTareas(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'servicio' => 'required|exists:servicio,id',
            'tareas' => 'required|array',
            'tiempos' => 'required|array',
            'tiempos.*' => 'required|integer|min:1', // Validar que cada tiempo sea un número entero mayor que 0
        ]);
    
        // Obtener el ID del servicio
        $servicioId = $request->servicio;
    
        // Asignar las tareas al servicio junto con el tiempo
        foreach ($request->tareas as $tareaId) {
            $tiempo = $request->tiempos[$tareaId];
    
            // Usar el método attach para agregar la tarea a la tabla pivot
            // Si ya existe, puedes usar sync para actualizar el tiempo
            DB::table('servicio_tarea')->updateOrInsert(
                ['cod_servicio' => $servicioId, 'cod_tarea' => $tareaId],
                ['tiempo' => $tiempo]
            );
        }
    
        return redirect()->route('servicios.asignarTareas', $servicioId)->with('success', 'Tareas asignadas exitosamente.');
    }
    
    public function editarTiempo($servicioId, $tareaId)
    {   
        
        // Encontrar el servicio y la tarea
        $servicio = Servicio::findOrFail($servicioId);
        $tarea = $servicio->tareasServicio()->findOrFail($tareaId); // Obtener la tarea asociada al servicio
    
        return view('servicios.editarTiempo', compact('servicio', 'tarea'));
    }
    
    
    public function actualizarTiempo(Request $request, $servicioId, $tareaId)
    {
        // Validar los datos del formulario
        $request->validate([
            'tiempo' => 'required|integer|min:1', // Validar que el tiempo sea un número entero mayor que 0
        ]);
    
        // Encontrar el servicio
        $servicio = Servicio::findOrFail($servicioId);
    
        // Actualizar el tiempo en la tabla pivot
        $servicio->tareasServicio()->updateExistingPivot($tareaId, ['tiempo' => $request->tiempo]);
    
        return redirect()->route('servicios.asignarTareas', $servicioId)->with('success', 'Tiempo actualizado exitosamente.');
    }

    public function eliminarTarea($servicioId, $tareaId)
    {
        // Encontrar el servicio
        $servicio = Servicio::findOrFail($servicioId);
    
        // Eliminar la relación en la tabla pivot
        $servicio->tareasServicio()->detach($tareaId);
    
        return redirect()->back()->with('success', 'Tarea desvinculada exitosamente.');
    }
}
