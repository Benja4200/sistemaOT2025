<?php

namespace App\Http\Controllers\ControladorOrdenes;

use App\Http\Controllers\Controller;
use App\Models\Avance;
use App\Models\Tarea;
use App\Models\Ot;
use App\Models\EstadoOt;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AvancesController extends Controller
{
    public function index($numero_ot)
    {
        // Cargar la OT con TODAS las relaciones necesarias para la vista y el cronómetro
        $orden = Ot::with([
            'avances',
            'estado', // ¡Necesario para la condición @if en Blade!
            'TareasOt.tarea', // Para calcular el tiempo estimado de tareas generales
            'DispositivoOT.tareaDispositivo.tarea' // Para calcular el tiempo estimado de tareas por dispositivo
        ])->where('numero_ot', $numero_ot)->firstOrFail();

        // Calcular el tiempo total estimado de tareas en minutos
        // Esto usa los datos de las relaciones que acabas de cargar
        $tiempoTotalTareasEstimado = $orden->tiempo_ot;

        // Si usas un campo como 'tiempo_ot' en la tabla 'ots' para guardar esto,
        // podrías usar: $tiempoTotalTareasEstimado = $orden->tiempo_ot ?? $tiempoTotalTareasEstimado;

        // Retornar la vista, pasando la orden (ahora con estado y tareas cargadas)
        // y el tiempo total estimado
        return view('editorAvance', compact('orden', 'tiempoTotalTareasEstimado'));
    }
    
    public function verAvances($numero_ot)
    {
        $orden = Ot::with('avances')->where('numero_ot', $numero_ot)->firstOrFail();
        return view('ordenes.avances', compact('orden'));
    }
    
    public function store(Request $request, $numero_ot)
    {
        $orden = Ot::where('numero_ot', $numero_ot)->firstOrFail();
        
        if ($orden->estado->descripcion_estado_ot === 'Finalizada') {
            return redirect()->route('ordenes.avances', $numero_ot)->with('error', 'No se pueden agregar más avances a una OT finalizada.');
        }
    
        $request->validate([
            'comentario_avance' => 'required|string',
            // 'fecha_avance' => 'required|date', // Removido
            'tiempo_avance' => 'required|integer',
            'imagen_avance' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);
    
        $imagePath = '';
    
        if ($request->hasFile('imagen_avance') && $request->file('imagen_avance')->isValid()) {
            $image = $request->file('imagen_avance');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'imagen/'.$imageName;
            $image->move('./imagen/', $imageName);
        }
    
        Avance::create([
            'comentario_avance' => $request->comentario_avance,
            'fecha_avance' => Carbon::now('America/Santiago'), // Fecha y hora actual
            'tiempo_avance' => $request->tiempo_avance,
            'cod_ot' => $numero_ot,
            'imagen' => $imagePath,
        ]);
        
        return redirect()->route('editor_avance', $numero_ot)->with('success', 'Avance agregado exitosamente.');
    }

    public function iniciarOt(Request $request, $numero_ot)
    {
        $orden = Ot::with('estado')->where('numero_ot', $numero_ot)->firstOrFail();

        if ($orden->estado->descripcion_estado_ot === 'Iniciada') {
            return redirect()->route('editor_avance', $numero_ot)->with('error', 'La OT ya está iniciada y no se puede volver a iniciar.');
        }

        $request->validate([
            'comentario_avance' => 'required|string',
            // 'fecha_avance' => 'required|date', // Removido si el formulario ya no lo envía
        ]);

        $estadoIniciada = EstadoOt::where('descripcion_estado_ot', 'Iniciada')->firstOrFail();
        
        $fechaActualChile = Carbon::now('America/Santiago');

        $orden->fecha_inicio_xot = $fechaActualChile; // Usar directamente la instancia Carbon
        $orden->cumulative_paused_seconds = 0;
        $orden->fecha_pausa_xot = null;
        $orden->fecha_ultima_reanudacion_xot = $fechaActualChile; // Usar directamente la instancia Carbon
        $orden->cod_estado_ot = $estadoIniciada->id;
        $orden->save(); 
        
        //auth()->user()->unreadNotifications
        //->where('data.orden_id', $orden->numero_ot)
        //->markAsRead();
        \DB::table('notifications')
        ->where('data->orden_id', $orden->numero_ot)
        ->update(['read_at' => \Carbon\Carbon::now()]);


        Avance::create([
           'comentario_avance' => $request->comentario_avance,
           'fecha_avance' => $fechaActualChile, // Fecha y hora actual para el avance de inicio
           'cod_ot' => $numero_ot,
           'tiempo_avance' => 0,
           'created_at' => now(), // O $fechaActualChile
           'updated_at' => now(), // O $fechaActualChile
       ]);

        return redirect()->route('editor_avance', $numero_ot)->with('success', 'OT Iniciada exitosamente.');
    }


    public function PendienteOt(Request $request, $numero_ot) // Recibe Request si usas un formulario
    {
        $orden = Ot::with('estado')->where('numero_ot', $numero_ot)->firstOrFail();

        if ($orden->estado->descripcion_estado_ot !== 'Iniciada') {
             return redirect()->route('editor_avance', $numero_ot)->with('error', 'Solo se puede poner Pendiente una OT que está Iniciada.');
        }

         $request->validate([
             'comentario_avance' => 'required|string',
         ]);

        $fechaActualChile = Carbon::now('America/Santiago');
        $orden->fecha_pausa_xot = $fechaActualChile; 

        $estadoPendiente = EstadoOt::where('descripcion_estado_ot', 'Pendiente')->firstOrFail(); 
        $orden->cod_estado_ot = $estadoPendiente->id;
        $orden->save(); 

         Avance::create([
            'comentario_avance' => $request->comentario_avance, 
            'fecha_avance' => $fechaActualChile, // Fecha y hora actual para el avance de pausa
            'cod_ot' => $numero_ot,
            'tiempo_avance' => 0, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('editor_avance', $numero_ot)->with('success', 'OT puesta en Pendiente.');
    }

    public function finalizar(Request $request, $numero_ot)
    {
        //dd($request);
        $orden = Ot::with(['estado', 'TareasOt.tarea', 'DispositivoOT.tareaDispositivo.tarea'])
                    ->where('numero_ot', $numero_ot)
                    ->firstOrFail();
     
        if ($orden->estado->descripcion_estado_ot === 'Finalizada') {
            return redirect()->route('editor_avance', $numero_ot)->with('error', 'La OT ya está finalizada y no se puede volver a finalizar.');
        }
     
        $request->validate([
            'comentario_avance' => 'required|string',
            'observacion_tarea' => 'array', // Validar que sea un array
            'observacion_tarea.*' => 'nullable|string|max:500'
        ]);
     
        $fechaActualChile = Carbon::now('America/Santiago');
     
        $orden->fecha_finalizacionexd_xot = $fechaActualChile; // Fecha y hora actual de finalización
     
        // LA LÍNEA $orden->fecha_pausa_xot = $fechaActualChile; FUE ELIMINADA DE AQUÍ
     
        // Calcular Tiempo Estimado en Segundos
        $tiempoTotalTareasEstimadoEnMinutos = 0;
        if ($orden->TareasOt) {
            $tiempoTotalTareasEstimadoEnMinutos += $orden->TareasOt->sum('tarea.tiempo_tarea');
        }
        if ($orden->DispositivoOT) {
            $tiempoTotalTareasEstimadoEnMinutos += $orden->DispositivoOT->sum(function ($dispositivoOt) {
                if ($dispositivoOt->tareaDispositivo) { // Asegurar que la relación existe
                    return $dispositivoOt->tareaDispositivo->sum('tarea.tiempo_tarea');
                }
                return 0;
            });
        }
        //$tiempoTotalTareasEstimadoEnSegundos = $tiempoTotalTareasEstimadoEnMinutos * 60;
        $tiempoTotalTareasEstimadoEnSegundos = $orden->tiempo_ot * 60;
        // Calcular Tiempo Activo Real en Segundos
        if ($orden->fecha_inicio_xot) {
            $fechaInicioCarbon = Carbon::parse($orden->fecha_inicio_xot);
            $duracionTotalEnSegundos = $fechaActualChile->diffInSeconds($fechaInicioCarbon);
            $pausaAcumuladaEnSegundos = $orden->cumulative_paused_seconds ?? 0;
            $tiempoActivoRealEnSegundos = $duracionTotalEnSegundos - $pausaAcumuladaEnSegundos;
            $orden->delay_seconds_xot = $tiempoTotalTareasEstimadoEnSegundos - $tiempoActivoRealEnSegundos;
        } else {
            $orden->delay_seconds_xot = null;
            Log::warning("OT {$numero_ot} finalizada sin fecha_inicio_xot. No se pudo calcular el retraso.");
        }
     
        $estadoFinalizada = EstadoOt::where('descripcion_estado_ot', 'Finalizada')->firstOrFail();
        $orden->cod_estado_ot = $estadoFinalizada->id;
        $fechaActualChile = Carbon::now('America/Santiago');
         $orden->fecha_pausa_xot = $fechaActualChile;
        $orden->save();
        
        $comentarioAvance = '';


        foreach ($request->observacion_tarea as $tareaId => $observacion) {
            $observacion = trim($observacion);
            
            if (!empty($observacion)) {
                // Sanitizar el comentario eliminando etiquetas HTML peligrosas
                $observacionLimpia = strip_tags($observacion); 
                
                // Obtener la tarea directamente desde la tabla
                $tareaModel = Tarea::find($tareaId);
                if ($tareaModel && $tareaModel->requiere_obs == "Si") {
                    $comentarioAvance .= "Observación de la tarea {$tareaModel->nombre_tarea}: \n {$observacionLimpia}\n\n";
                }
            }
        }
        
        // Sanitizar el comentario de avance final antes de guardarlo
        $comentarioAvance .= "Comentario de avance final: \n" . strip_tags($request->comentario_avance);
                
        Avance::create([
            'comentario_avance' => $comentarioAvance,
            'fecha_avance' => $fechaActualChile, // Fecha y hora actual para el avance final
            'tiempo_avance' => 0,
            'cod_ot' => $numero_ot,
            'created_at' => now(),
            'updated_at' => now()
        ]);
     
        return redirect()->route('editor_avance', $numero_ot)->with('success', 'OT finalizada y avance agregado exitosamente.');
    }

    public function actualizarComentarioAvance(Request $request, $id_avance)
    {
        // Si también quieres que la fecha de actualización del avance sea "ahora":
        // 1. Remueve 'fecha_avance' de la validación.
        // 2. Cambia $avance->fecha_avance = $request->fecha_avance; a $avance->fecha_avance = Carbon::now('America/Santiago');
        // Por ahora, mantendré la edición de fecha como estaba, ya que la petición se centró en la creación.
        $request->validate([
            'comentario_avance' => 'required|string|max:255',
            'fecha_avance' => 'required|date', // Mantener si se desea editar la fecha de un avance pasado
            'tiempo_avance' => 'required|integer',
            'imagen_avance' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'cod_ot' => 'required|string',
        ]);
    
        try {
            $avance = Avance::findOrFail($id_avance);
    
            $imagePath = $avance->imagen;
            if ($request->hasFile('imagen_avance') && $request->file('imagen_avance')->isValid()) {
                if ($avance->imagen && file_exists(public_path($avance->imagen))) {
                    unlink(public_path($avance->imagen));
                }
                $image = $request->file('imagen_avance');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = 'imagen/' . $imageName;
                $image->move('./imagen/', $imageName);
            }
    
            $avance->comentario_avance = $request->comentario_avance;
            $avance->fecha_avance = $request->fecha_avance; // Si se mantiene editable
            // Si se quiere que sea la fecha actual al editar:
            // $avance->fecha_avance = Carbon::now('America/Santiago');
            $avance->tiempo_avance = $request->tiempo_avance;
            $avance->cod_ot = $request->cod_ot;
            $avance->imagen = $imagePath;
            $avance->save();
    
            return response()->json(['success' => true, 'mensaje' => 'Avance actualizado exitosamente.']);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el avance: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'mensaje' => 'Error al actualizar el avance: ' . $e->getMessage()]);
        }
    }
    
    public function eliminarctx($id_avance)
    {
    try {
        // Buscar el avance por ID
        $avance = Avance::findOrFail($id_avance);
        
        // Eliminar la imagen asociada si existe
        if ($avance->imagen && file_exists(public_path($avance->imagen))) {
            unlink(public_path($avance->imagen));
        }

        // Eliminar el avance
        $avance->delete();

        return response()->json(['success' => true, 'mensaje' => 'Avance eliminado exitosamente.']);
    } catch (\Exception $e) {
        // Log the exception for better debugging
        \Log::error('Error al eliminar el avance: ' . $e->getMessage());
        return response()->json(['success' => false, 'mensaje' => 'Error al eliminar el avance: ' . $e->getMessage()]);
    }
}
public function actualizar(Request $request, $id)
{
    // Validación de datos
    $validated = $request->validate([
        'comentario_avance' => 'required|string',
        'fecha_avance' => 'required|date',
        'tiempo_avance' => 'required|numeric',
        'imagen_avance' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
    ]);

    // Buscar el avance
    $avance = Avance::findOrFail($id);

    // Actualizar datos básicos
    $avance->comentario_avance = $request->comentario_avance;
    $avance->fecha_avance = $request->fecha_avance;
    $avance->tiempo_avance = $request->tiempo_avance;

    // Manejar imagen si se sube una nueva
   $imagePath = $avance->imagen;
    if ($request->hasFile('imagen_avance') && $request->file('imagen_avance')->isValid()) {
        // Eliminar imagen anterior si existe
        if ($avance->imagen && file_exists(public_path($avance->imagen))) {
            unlink(public_path($avance->imagen));
        }

        // Subir la nueva imagen
        $image = $request->file('imagen_avance');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = 'imagen/' . $imageName;
        $image->move('./imagen/', $imageName);
        
    }
    $avance->imagen = $imagePath;
    $avance->save();

    return response()->json([
        'success' => true,
        'mensaje' => 'Avance actualizado correctamente',
    ]);
    }
    public function reanudarOt($numero_ot) // Puedes añadir Request $request si tu formulario POST envía datos
    {
        // Cargar la OT, incluyendo la relación estado.
        // Si el campo está en $casts, fecha_pausa_xot ya debería ser Carbon aquí.
        $orden = Ot::with('estado')->where('numero_ot', $numero_ot)->firstOrFail();

        // 1. Verificar el estado actual: Debe ser Pendiente.
        if ($orden->estado->descripcion_estado_ot != 'Pendiente' && $orden->estado->descripcion_estado_ot != 'Finalizada') {
             Log::warning('Intento de reanudar OT ' . $numero_ot . ' con estado incorrecto: ' . ($orden->estado->descripcion_estado_ot ?? 'N/D'));
             return redirect()->route('editor_avance', $numero_ot)->with('error', 'Solo se puede reanudar una OT que está en estado Pendiente o Finalizada.');
        }
        
        $duracionUltimaPausaCalculada = 0; // Inicializamos para el mensaje


        // 2. *** LÓGICA PARA CALCULAR Y ACUMULAR EL TIEMPO DE LA ÚLTIMA PAUSA ***
        // Solo procedemos si hay una fecha de pausa registrada
        if ($orden->fecha_pausa_xot) { 
            try {
                // *** ASEGURAR QUE $fechaPausa ES INSTANCIA DE CARBON ***
                // Si no lo es (a pesar del $casts), la parseamos explícitamente como fallback.
                $fechaPausa = $orden->fecha_pausa_xot; // Valor inicial
                if (!($fechaPausa instanceof Carbon)) {
                     // Si no es Carbon, intentar parsearla. Esto maneja el caso donde $casts no funciona.
                     $fechaPausa = Carbon::parse($fechaPausa);
                     Log::warning('Fecha pausa para OT ' . $numero_ot . ' no casteada automáticamente a Carbon. Parseada manualmente.');
                }
                // Si $fechaPausa era null o inválida para parsear, Carbon::parse puede lanzar excepción.
                // Si llegamos aquí, $fechaPausa debería ser Carbon (o null/false si Carbon::parse falló y no lanzó excepción).
                // Verificamos de nuevo si es Carbon antes de usar getTimestampMs.
                if ($fechaPausa instanceof Carbon) {
                     $ahora = Carbon::now()->setTimezone('America/Santiago'); // Capturamos la hora actual

                    // *** CALCULAR LA DIFERENCIA EN SEGUNDOS USANDO TIMESTAMPS EN MILISEGUNDOS ***
                    $pausaTimestampMs = $fechaPausa->getTimestampMs(); // Obtener timestamp de pausa en ms
                    $ahoraTimestampMs = $ahora->getTimestampMs(); // Obtener timestamp actual en ms

                    // La diferencia en milisegundos
                    $diffInMilliseconds = $ahoraTimestampMs - $pausaTimestampMs; 
                    
                    // Convertir a segundos, asegurar no negativo y obtener la parte entera (floor)
                    $duracionCalculadaParaSumar = floor(max(0, $diffInMilliseconds / 1000)); 
                    // *** FIN CALCULO CON TIMESTAMPS ***


                    // Loguear la duración calculada 
                    if ($duracionCalculadaParaSumar <= 0) { 
                         Log::warning('Duración de pausa calculada (via ms) <= 0 para OT ' . $numero_ot . '. Pausa Ms: ' . $pausaTimestampMs . ', Ahora Ms: ' . $ahoraTimestampMs . ', Diff Ms: ' . $diffInMilliseconds . ', Raw diffInSeconds: ' . $ahora->diffInSeconds($fechaPausa, false));
                    } else {
                         Log::info('Duración de última pausa calculada (via ms) > 0 para OT ' . $numero_ot . ': ' . $duracionCalculadaParaSumar . ' segundos.');
                    }
                    
                    // *** Acumular la pausa ***
                    // Sumamos la duración calculada (que es >= 0).
                    $orden->cumulative_paused_seconds += $duracionCalculadaParaSumar; 
                    Log::info('Pausa acumulada sumada exitosamente para OT ' . $numero_ot . '. Duración sumada: ' . $duracionCalculadaParaSumar . ', Nuevo Total Acumulado: ' . $orden->cumulative_paused_seconds);

                    // Guardamos la duración calculada para el mensaje de SweetAlert
                    $duracionUltimaPausaCalculada = $duracionCalculadaParaSumar;

                } else {
                    // Si después del parseo no es Carbon, logueamos el problema
                    Log::error('Fecha pausa para OT ' . $numero_ot . ' no es instancia de Carbon despues de parseo. Valor: ' . $orden->fecha_pausa_xot);
                    $duracionUltimaPausaCalculada = 0; // Aseguramos 0
                }

            } catch (\Exception $e) {
                 // Si hay una excepción al parsear o calcular, logueamos el error.
                 Log::error('Excepción inesperada al calcular duración de pausa (via ms) en reanudarOt para OT ' . $numero_ot . ': ' . $e->getMessage() . '. Stack: ' . $e->getTraceAsString());
                 $duracionUltimaPausaCalculada = 0; // Aseguramos 0 para el mensaje si hay excepción.
            }
            
            // Limpiar la fecha_pausa_xot una vez que intentamos usarla (incluso si hubo error)
            $orden->fecha_pausa_xot = null; 

        } else {
             // Si no hay fecha_pausa_xot registrada (y el estado era Pendiente), esto es inesperado.
             Log::warning('OT ' . $numero_ot . ' en estado Pendiente pero sin fecha_pausa_xot. No se acumuló pausa.');
             $duracionUltimaPausaCalculada = 0; // Aseguramos 0
        }
        // *** FIN LÓGICA CÁLCULO Y ACUMULACIÓN ***


        // 3. Encontrar el objeto del estado "Iniciada".
        $estadoIniciada = EstadoOt::where('descripcion_estado_ot', 'Iniciada')->firstOrFail();

        // 4. Cambiar el estado de la OT al estado "Iniciada".
        $orden->cod_estado_ot = $estadoIniciada->id;

        // 5. Registrar la fecha y hora de la reanudación.
        // Asegúrate de que 'fecha_ultima_reanudacion_xot' esté en el $casts del modelo.
        // Usamos la instancia de Carbon capturada al principio.
        $orden->fecha_ultima_reanudacion_xot = $ahora; 


        // 6. *** Guardar todos los cambios (estado, pausa acumulada, fechas) en la base de datos. ***
        $orden->save(); 
        Log::info('OT ' . $numero_ot . ' guardada después de Reanudar. Estado: Iniciada, Pausa Acumulada final: ' . $orden->cumulative_paused_seconds);


        // Opcional: Crear un Avance automático
        // use App\Models\Avance;
        // Avance::create([
        //    'comentario_avance' => 'OT Reanudada',
        //    'fecha_avance' => $ahora, // Usa la hora de reanudación
        //    'cod_ot' => $numero_ot,
        //    'tiempo_avance' => 0, 
        //    'created_at' => now(),
        //    'updated_at' => now(),
        // ]);

        // 7. Redirigir de vuelta a la vista de avances, incluyendo la duración calculada en el mensaje para verificación.
        return redirect()->route('editor_avance', $numero_ot)->with('success', 'OT Reanudada exitosamente. Última pausa calculada: ' . $duracionUltimaPausaCalculada . ' segundos.');
    }
}