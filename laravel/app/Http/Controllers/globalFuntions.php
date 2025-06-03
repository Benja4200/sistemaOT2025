<?php

namespace App\Http\Controllers;

use App\Models\Avance;
use App\Models\EstadoOt;
use App\Models\Ot;
use Illuminate\Http\Request;
use App\Models\Subcategoria;
use App\Models\Linea;
use App\Models\Sublinea;
use App\Models\TecnicoServicio;
use App\Models\Tarea;
use App\Models\TareaOt;
use App\Models\Tecnico;
use Illuminate\Support\Facades\DB;
use App\Models\TipoServicio;
use App\Models\DispositivoOt;
use App\Models\Dispositivo;
use App\Models\TareaDispositivo;
use App\Models\Servicio;
use Carbon\Carbon;

class globalFuntions extends Controller
{
    public function redireccionoA_editorAvance($numero_ot)
    {
        // Cargar la OT con TODAS las relaciones necesarias para la vista y el cronómetro.
        // Los campos fecha_inicio_xot, fecha_pausa_xot, cumulative_paused_seconds
        // y fecha_ultima_reanudacion_xot (si existe en DB) son campos directos de la tabla 'ots',
        // por lo que se cargan automáticamente con el modelo Ot principal.
        $orden = Ot::with([
            'avances', // Para mostrar la lista de avances
            'estado', // Necesario para la condición @if en Blade y mostrar el estado actual
            'TareasOt.tarea', // Necesario para calcular el tiempo estimado de tareas generales
            'DispositivoOT.tareaDispositivo.tarea' // Necesario para calcular el tiempo estimado de tareas por dispositivo
        ])->where('numero_ot', $numero_ot)->firstOrFail();

        // Calcular el tiempo total estimado de tareas en minutos.
        // Utilizamos los datos de las relaciones TareasOt y DispositivoOT que acabamos de cargar.
        // Este cálculo ya verificamos con dd() que te da 6 minutos.
        $tiempoTotalTareasEstimado = $orden->tiempo_ot;

        // Si tienes un campo como 'tiempo_ot' en la tabla 'ots' donde guardas la suma estimada
        // al crear/planificar la OT, podrías usarlo como fuente principal si confías más en él:
        // $tiempoTotalTareasEstimado = $orden->tiempo_ot ?? $tiempoTotalTareasEstimado;
        // Asegúrate de que este campo se actualice correctamente en los métodos de creación/inicio de OT si lo usas.


        // Retornar la vista de avances, pasando el objeto $orden completo y el tiempo estimado.
        // Los campos relacionados con pausa/reanudación (fecha_inicio_xot, fecha_pausa_xot,
        // cumulative_paused_seconds, fecha_ultima_reanudacion_xot) están disponibles
        // como propiedades del objeto $orden en la vista.
        return view('ordenespropiasxtecnico.editorAvance', compact('orden', 'tiempoTotalTareasEstimado'));
    }

    public function store(Request $request, $numero_ot)
    {
        // obtener la OT
        $orden = Ot::where('numero_ot', $numero_ot)->firstOrFail();

        // verificar si la OT esta finalizada
        if ($orden->estado->descripcion_estado_ot === 'Finalizada') {
            return redirect()->route('ordenes.avances', $numero_ot)->with('error', 'No se pueden agregar más avances a una OT finalizada.');
        }

        $request->validate([
            'comentario_avance' => 'required|string',
            'fecha_avance' => 'required|date',
            'tiempo_avance' => 'required|integer',
        ]);

        Avance::create([
            'comentario_avance' => $request->comentario_avance,
            'fecha_avance' => $request->fecha_avance,
            'tiempo_avance' => $request->tiempo_avance,
            'cod_ot' => $numero_ot,
        ]);

        return redirect()->route('ordenes.avances', $numero_ot)->with('success', 'Avance agregado exitosamente.');
    }

    public function finalizar(Request $request, $numero_ot)
    {
        // obtener la OT
        $orden = Ot::where('numero_ot', $numero_ot)->firstOrFail();

        // verificar si la OT ya esta finalizada
        if ($orden->estado->descripcion_estado_ot === 'Finalizada') {
            return redirect()->route('ordenes.avances', $numero_ot)->with('error', 'La OT ya está finalizada y no se puede volver a finalizar.');
        }

        // validar el avance final
        $request->validate([
            'comentario_avance' => 'required|string',
            'fecha_avance' => 'required|date',
            'tiempo_avance' => 'required|integer',
        ]);

        // Obtener el estado 'Finalizada' de la tabla `estado_ot`
        $estadoFinalizada = EstadoOt::where('descripcion_estado_ot', 'Finalizada')->firstOrFail();

        // Cambiar el estado de la OT a "Finalizada"
        $orden->cod_estado_ot = $estadoFinalizada->id;
        $orden->save();

        // Crear el último avance
        Avance::create([
            'comentario_avance' => $request->comentario_avance,
            'fecha_avance' => $request->fecha_avance,
            'tiempo_avance' => $request->tiempo_avance,
            'cod_ot' => $numero_ot,
        ]);

        return redirect()->route('ordenes.avances', $numero_ot)->with('success', 'OT finalizada y avance agregado exitosamente.');
    }
    
    public function getSubcategorias($categoriaId)
    {
        $subcategorias = Subcategoria::where('cod_categoria', $categoriaId)->get();

        return response()->json($subcategorias);
    }
    
    public function getLineas($subcategoriaId)
    {
        $lineas = Linea::where('cod_subcategoria', $subcategoriaId)->get();

        return response()->json($lineas);
    }
    
    public function getSublineas($lineaId)
    {
        $sublineas = Sublinea::where('cod_linea', $lineaId)->get();

        return response()->json($sublineas);
    }
    
    public function buscadordecronogr($fecha_inicio, $fecha_termino, $tecnicoid, $servicioxs)
    {
        
        $tecnicoparawhere = TecnicoServicio::where('cod_tecnico', $tecnicoid)->get();
        
        $servicioparawhere = TecnicoServicio::where('cod_servicio', $servicioxs)->get();
        
        
        
        return response()->json([
            'datoxtecnicobuscador' => $tecnicoparawhere,
            'datoxserviciobuscador' => $servicioparawhere
            ]);
        
    }
    
    public function datosparacrono() {
        // Obtener información de los técnicos y sus servicios
        $all_tecnicos_data = Tecnico::with('tecnicoServicio.servicio')->get();
    
        $resultados = [];
    
        // Iterar sobre la información de los técnicos
        foreach ($all_tecnicos_data as $tecnico) {
    
            // Buscar las órdenes de trabajo pendientes para cada técnico
            $ordenes_asignadas_pendientes = Ot::where('cod_tecnico_encargado', $tecnico->id)
                ->where('cod_estado_ot', 2)
                ->get();
    
            // Obtener las órdenes de trabajo iniciadas
            $ordenes_asignadas_iniciadasx = Ot::where('cod_tecnico_encargado', $tecnico->id)
                ->where('cod_estado_ot', 1)
                ->get();
    
            // Obtener las órdenes de trabajo finalizadas
            $ordenes_asignadas_finali = Ot::where('cod_tecnico_encargado', $tecnico->id)
                ->where('cod_estado_ot', 3)
                ->get();
    
            // Contar las órdenes iniciadas y finalizadas
            $cantidad_ot_ini = $ordenes_asignadas_iniciadasx->count();
            $cantidad_ot_finishem = $ordenes_asignadas_finali->count();
    
            // Inicializar las variables de fecha
            $fecha_de_inicio_x_tecn = null;
            $fecha_de_finaliz_x_tecn = null;
    
            // Obtener la fecha de inicio más temprana de las órdenes iniciadas
            foreach ($ordenes_asignadas_iniciadasx as $orden) {
                $fecha_inicio_ot = $orden->fecha_inicio_xot;
                if ($fecha_inicio_ot) {
                    if (!$fecha_de_inicio_x_tecn || Carbon::parse($fecha_inicio_ot)->lt($fecha_de_inicio_x_tecn)) {
                        $fecha_de_inicio_x_tecn = Carbon::parse($fecha_inicio_ot);
                    }
                }
            }
    
            // Obtener la fecha de finalización más tardía de las órdenes finalizadas
            foreach ($ordenes_asignadas_finali as $orden) {
                $fecha_final_ot = $orden->fecha_finalizacionexd_xot;
                if ($fecha_final_ot) {
                    if (!$fecha_de_finaliz_x_tecn || Carbon::parse($fecha_final_ot)->gt($fecha_de_finaliz_x_tecn)) {
                        $fecha_de_finaliz_x_tecn = Carbon::parse($fecha_final_ot);
                    }
                }
            }
    
            // Convertir las fechas a formato ISO 8601 si no son nulas
            $fecha_de_inicio_x_tecn = $fecha_de_inicio_x_tecn ? $fecha_de_inicio_x_tecn->toDateString() : null;
            $fecha_de_finaliz_x_tecn = $fecha_de_finaliz_x_tecn ? $fecha_de_finaliz_x_tecn->toDateString() : null;
            
            //dd($fecha_de_inicio_x_tecn, $fecha_de_finaliz_x_tecn);
    
            // Si no hay órdenes asignadas pendientes, continuar con el siguiente técnico
            if ($ordenes_asignadas_pendientes->isEmpty()) {
                continue;
            }
    
            $ordenes_info = [];
    
            // Iterar sobre las órdenes asignadas pendientes
            foreach ($ordenes_asignadas_pendientes as $orden) {
    
                // Obtener dispositivos asociados
                $dispositivos = DispositivoOt::where('cod_ot', $orden->numero_ot)->get();
    
                $tareas_dispositivos = [];
                $total_tiempo_tareas = 0;
                $cantidad_tareas = 0;
    
                // Iterar sobre los dispositivos asociados a cada OT
                foreach ($dispositivos as $dispositivo) {
    
                    // Obtener las tareas asociadas a cada dispositivo
                    $tareas = TareaDispositivo::where('cod_dispositivo_ot', $dispositivo->id)
                        ->with('tarea')
                        ->get();
    
                    // Iterar sobre las tareas y agregar datos
                    foreach ($tareas as $tarea) {
                        if ($tarea->tarea) {
                            $tareas_dispositivos[] = [
                                'id_tarea' => $tarea->tarea->id,
                                'nombre_tarea' => $tarea->tarea->nombre_tarea,
                                'tiempo_tarea' => isset($tarea->tarea->tiempo_tarea) ? $tarea->tarea->tiempo_tarea : 0,
                                'dispositivo' => $tarea->cod_dispositivo_ot
                            ];
    
                            //$total_tiempo_tareas += $tarea->tarea->tiempo_tarea;
                            
                            $cantidad_tareas++;
                        }
                    }
                    $total_tiempo_tareas = $orden->tiempo_ot;
                    // Información resumen de los dispositivos
                    $tareas_dispositivos[] = [
                        'dispositivo' => $dispositivo->id,
                        'cantidad_tareas' => $cantidad_tareas,
                        'total_tiempo_tareas' => $total_tiempo_tareas
                    ];
                }
    
                // Agregar la información de la OT a la lista
                $ordenes_info[] = [
                    'codigo_ot' => $orden->numero_ot,
                    'fecha_inicio' => $fecha_de_inicio_x_tecn,
                    'fecha_termino' => $fecha_de_finaliz_x_tecn,
                    'dispositivos' => $dispositivos,
                    'tareas' => $tareas_dispositivos
                ];
            }
    
            // Agregar la información del técnico y sus órdenes
            $resultados[] = [
                'nombre_tecnico' => $tecnico->nombre_tecnico,
                'ordenes_asignadas_key_pendientes' => $ordenes_info,
                'cantidad_ordenes_pendientes' => $ordenes_asignadas_pendientes->count(),
                'canti_ini' => $cantidad_ot_ini,
                'canti_finalixz' => $cantidad_ot_finishem,
                'tipo_servicios' => $tecnico->tecnicoServicio->pluck('servicio.nombre_servicio')
            ];
        }
    
        // Responder con la información
        return response()->json([
            'datostecnicosyservicio' => $resultados,
            'tareasconservicios' => '$datostareasconserviciox',
            'tareas_con_ordenes' => '$datosTareaconOrden'
        ]);
    }



    public function crearTipoServicio(Request $request)
    {
        $request->validate([
            'nombre_tipo_servicio' => 'required|string|max:255',
        ]);

        TipoServicio::create([
            'descripcion_tipo_servicio' => $request->nombre_tipo_servicio,
        ]);

        return redirect()->route('servicios.index')
            ->with('success', 'Tipo de servicio creado exitosamente.');
    }
    
    public function buscarTecnicoxd(Request $request) {
        // Obtenemos el término de búsqueda de la petición
        $searchTerm = $request->input('searchtecnico');
        
        // Si hay un término de búsqueda, filtramos los datos
        if ($searchTerm) {
            $datostecnicosyservicio = TecnicoServicio::with(['tecnico', 'servicio'])
                ->whereHas('tecnico', function ($query) use ($searchTerm) {
                    $query->where('nombre_tecnico', 'like', "%$searchTerm%");
                })
                ->orWhereHas('servicio', function ($query) use ($searchTerm) {
                    $query->where('nombre_servicio', 'like', "%$searchTerm%");
                })
                ->take(4)
                ->get(['id', 'cod_tecnico', 'cod_servicio', 'created_at', 'updated_at']);
        } else {
            // Si no hay término de búsqueda, obtener todos los datos
            $datostecnicosyservicio = TecnicoServicio::with(['tecnico', 'servicio'])
                ->take(4)
                ->get(['id', 'cod_tecnico', 'cod_servicio', 'created_at', 'updated_at']);
        }
    
        // Obtener tareas y otras relaciones
        $datostareasconserviciox = Tarea::with(['servicio'])
            ->take(4)
            ->get(['id', 'nombre_tarea', 'tiempo_tarea']);
        
        $datosTareaconOrden = TareaOt::with(['tarea', 'ot'])
            ->take(4)
            ->get(['id', 'cod_tarea', 'cod_ot', 'created_at']);
    
        $resultados = [];
    
        // Procesar los resultados
        foreach ($datostecnicosyservicio as $item) {
            // Obtener el nombre del técnico y el nombre del servicio
            $nombreTecnico = $item->tecnico ? $item->tecnico->nombre_tecnico : 'N/A';
            $nombreServicio = $item->servicio ? $item->servicio->nombre_servicio : 'N/A';
    
            // Filtrar tareas por el cod_servicio
            $tareas = Tarea::where('cod_servicio', $item->cod_servicio)->get();
            $infoTecnico = DB::table('tecnico')->where('id', $item->cod_tecnico)->first();
            
            // Calcular el número de tareas y el tiempo total de las tareas
            $tareasCount = $tareas->count();
            $tiempoTarea = $tareas->sum('tiempo_tarea');
            
            // Añadir los resultados al array
            $resultados[] = [
                'nombre_tecnico' => $infoTecnico ? $infoTecnico->nombre_tecnico : 'N/A',
                'fecha_inicio' => $item->created_at,
                'fecha_termino' => $item->updated_at,
                'tareas_count' => $tareasCount,
                'tiempo_tarea' => $tiempoTarea,
                'tipo_servicio_que_realiza_el_tecnico' => $nombreServicio,
                'detalle_tarea' => $tareas,
            ];
        }
    
        // Retornar los resultados en formato JSON
        return response()->json([
            'datostecnicosyservicio' => $resultados,
            'tareasconservicios' => $datostareasconserviciox,
            'tareas_con_ordenes' => $datosTareaconOrden
        ]);
    }

}

