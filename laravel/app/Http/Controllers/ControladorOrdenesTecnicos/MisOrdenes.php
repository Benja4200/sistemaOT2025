<?php

namespace App\Http\Controllers\ControladorOrdenesTecnicos;

use App\Http\Controllers\Controller;
use App\Models\Avance;
use App\Models\Ot;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActividadExtra;
use App\Models\Dispositivo;
use App\Models\TareaDispositivo;
use App\Models\Modelo;

class MisOrdenes extends Controller
{

    public function ordentecnico(Request $request)
{
    $showAll = $request->input('show_all', 'false');  // usamos 'false' como valor default
    $user = Auth::user();
    
    // Para el técnico, obtenemos sus notificaciones sin leer
    $notificaciones = $user->unreadNotifications;
    
    // Si no hay usuario autenticado redirige al login.
    if (!$user) {
        return redirect()->route('login');
    }

    // Verificar si el usuario tiene el rol 'Tecnicos'
    if ($user->hasRole('Tecnicos')) {
        // Obtener el técnico relacionado con el usuario
        $datosTecnicoxUsuario = $user->tecnico;
        if ($datosTecnicoxUsuario) {

            // Se obtiene la lista de órdenes en las que el técnico es encargado
            // o es parte del equipo (según la tabla equipo_tecnico, donde
            // el campo cod_ot hace referencia al número de OT).
            $datosMisOrdenes = Ot::where('cod_tecnico_encargado', $datosTecnicoxUsuario->id)
                ->orWhereIn('numero_ot', function ($query) use ($datosTecnicoxUsuario) {
                    $query->select('cod_ot')
                          ->from('equipo_tecnico')
                          ->where('cod_tecnico', $datosTecnicoxUsuario->id);
                })
                ->orderBy('numero_ot', 'desc')
                ->paginate(8)
                ->appends(['show_all' => $showAll]);

            // Lo mismo para el conteo y para la exportación a Excel
            $cantidadOrdenes = Ot::where('cod_tecnico_encargado', $datosTecnicoxUsuario->id)
                ->orWhereIn('numero_ot', function ($query) use ($datosTecnicoxUsuario) {
                    $query->select('cod_ot')
                          ->from('equipo_tecnico')
                          ->where('cod_tecnico', $datosTecnicoxUsuario->id);
                })->count();

            $paraExcel = Ot::where('cod_tecnico_encargado', $datosTecnicoxUsuario->id)
                ->orWhereIn('numero_ot', function ($query) use ($datosTecnicoxUsuario) {
                    $query->select('cod_ot')
                          ->from('equipo_tecnico')
                          ->where('cod_tecnico', $datosTecnicoxUsuario->id);
                })->get();

            $vistaSeleccionada = $showAll === 'true' ? 'todas' : 'asignadas';

            return view('ordenespropiasxtecnico.misordenes', compact(
                'datosMisOrdenes',
                'cantidadOrdenes',
                'paraExcel',
                'vistaSeleccionada',
                'notificaciones'
            ));
        } else {
            return redirect()->intended('/error404'); // Si no hay técnico asociado
        }
    }

    // Si el usuario no tiene el rol 'Tecnicos'
    return redirect()->route('login.submit');
}



    public function detallers($id)
{
    // Cargo la OT con todas las relaciones necesarias
    $ordenesTecnicox = Ot::with([
        'estado', 
        'tipoVisita', 
        'prioridad', 
        'tipo', 
        'contacto', 
        'servicio', 
        'tecnicoEncargado', 
        'contactoOt', 
        'EquipoTecnico',
        'DispositivoOT',
        'TareasOt',
        'avances'
    ])
    ->where('numero_ot', $id)
    ->get();

    // Si no existe, redirect
    if ($ordenesTecnicox->isEmpty()) {
        return redirect()
               ->route('error.route')
               ->with('error', 'Orden no encontrada.');
    }

    // Toma la primera (y única) OT
    $orden = $ordenesTecnicox->first();

    // 1) Equipo técnico
    if ($orden->EquipoTecnico) {
        $obteniendo_equipo_tecnico = $orden->EquipoTecnico->map(function($equipo) {
            return $equipo->tecnico
                   ? $equipo->tecnico->nombre_tecnico
                   : 'Técnico no disponible';
        });
    } else {
        $obteniendo_equipo_tecnico = collect(['Técnico no disponible']);
    }

    // 2) Dispositivos
    if ($orden->DispositivoOT) {
        $obteniendo_dispositivos = $orden->DispositivoOT->map(function($dispOt) {
            $disp = Dispositivo::find($dispOt->cod_dispositivo);
            return [
                'numero_serie' => $disp
                    ? $disp->numero_serie_dispositivo
                    : 'N/D',
                'modelo'       => ($disp && $disp->modelo)
                    ? $disp->modelo->nombre_modelo
                    : 'Modelo no disponible',
            ];
        });
    } else {
        $obteniendo_dispositivos = collect([[
            'numero_serie' => 'Dispositivo no disponible',
            'modelo'       => 'Modelo no disponible'
        ]]);
    }

    // 3) Tareas (de todos los dispositivos)
    if ($orden->DispositivoOT) {
        $obteniendo_tareas = collect();
        foreach ($orden->DispositivoOT as $dispOt) {
            $tareas = TareaDispositivo::where('cod_dispositivo_ot', $dispOt->id)
                ->with('tarea')
                ->get()
                ->map(function($td) {
                    return [
                        'nombre' => $td->tarea
                            ? $td->tarea->nombre_tarea
                            : 'Tarea no disponible',
                        'tiempo' => $td->tarea
                            ? $td->tarea->tiempo_tarea
                            : 'Tiempo no disponible',
                    ];
                });
            $obteniendo_tareas = $obteniendo_tareas->merge($tareas);
        }
    } else {
        $obteniendo_tareas = collect([[
            'nombre' => 'Tarea no disponible',
            'tiempo' => 'Tiempo no disponible'
        ]]);
    }

    // 4) Avances registrados
    $avanceXorden = Avance::where('cod_ot', $id)->get();
    $cantidad_avanceXorden = $avanceXorden->count();

    // 5) Actividades extra asociadas
    $actividadesExtras = ActividadExtra::where('cod_ot', $id)->get();

    // Paso todo a la vista
    return view('ordenespropiasxtecnico.detalleOrdenTecnico', compact(
        'ordenesTecnicox',
        'avanceXorden',
        'cantidad_avanceXorden',
        'obteniendo_equipo_tecnico',
        'obteniendo_dispositivos',
        'obteniendo_tareas',
        'actividadesExtras'
    ));
}


    public function weaxd()
    {
        return redirect()->route('login.submit');
    }

    public function generarPDF($numero_ot)
    {
        // Obtener la orden con el número_ot
        $ordenesDeTecnicos = Ot::with('contacto.sucursal.cliente', 'tipo', 'tecnicoEncargado', 'EquipoTecnico', 'servicio', 'prioridad')
            ->where('numero_ot', $numero_ot)
            ->firstOrFail();  // obtener solo la orden especifica

        // Convertir la imagen a base64 (si es una imagen local en el servidor)
        $imagePath = public_path('assets/image/logo-small.png');
        $imageData = base64_encode(file_get_contents($imagePath));

        // si la orden existe
        if (!$ordenesDeTecnicos) {
            return redirect()->back()->with('error', 'Orden no encontrada');
        }

        // generamo el PDF usando la vista y pasando los datos de la orden
        $pdf = Pdf::loadView('ordenespropiasxtecnico.pdf_orden', compact('ordenesDeTecnicos', 'imageData'));

        // retornamo el PDF para ser descargado
        return $pdf->download('orden_trabajo_' . $numero_ot . '.pdf');
    }

    public function buscarOrdenes(Request $request)
    {
        $user = Auth::user();
        $showAll = $request->input('show_all', 'false');
        $notificaciones = $user->unreadNotifications;
        
        if (!$user) {
            return redirect()->route('login');
        }
    
        if ($user->hasRole('Tecnicos')) {
            $datosTecnicoxUsuario = $user->tecnico;
    
            if ($datosTecnicoxUsuario) {
                $searchTerm = $request->input('search', '');
    
                // Iniciamos la consulta básica para órdenes en las que el técnico es O bien encargado
                // o participa en el equipo_tecnico
                $query = Ot::where(function ($q) use ($datosTecnicoxUsuario) {
                    $q->where('cod_tecnico_encargado', $datosTecnicoxUsuario->id)
                      ->orWhereIn('numero_ot', function ($q2) use ($datosTecnicoxUsuario) {
                          $q2->select('cod_ot')
                             ->from('equipo_tecnico')
                             ->where('cod_tecnico', $datosTecnicoxUsuario->id);
                      });
                });
    
                // Aplicar filtros de búsqueda
                if (!empty($searchTerm)) {
                    if (is_numeric($searchTerm)) {
                        $query->where('numero_ot', $searchTerm);
                    } else {
                        $query->where(function ($q) use ($searchTerm) {
                            $q->orWhere('descripcion_ot', 'like', '%' . $searchTerm . '%')
                              ->orWhere('comentario_ot', 'like', '%' . $searchTerm . '%')
                              ->orWhereHas('contacto', function ($q) use ($searchTerm) {
                                  $q->where('nombre_contacto', 'like', '%' . $searchTerm . '%');
                              })
                              ->orWhereHas('contacto.sucursal.cliente', function ($q) use ($searchTerm) {
                                  $q->where('nombre_cliente', 'like', '%' . $searchTerm . '%');
                              })
                              ->orWhereHas('estado', function ($q) use ($searchTerm) {
                                  $q->where('descripcion_estado_ot', 'like', '%' . $searchTerm . '%');
                              })
                              ->orWhereHas('servicio', function ($q) use ($searchTerm) {
                                  $q->where('nombre_servicio', 'like', '%' . $searchTerm . '%');
                              })
                              ->orWhereHas('tecnicoEncargado', function ($q) use ($searchTerm) {
                                  $q->where('nombre_tecnico', 'like', '%' . $searchTerm . '%');
                              });
                        });
                    }
                }
    
                // Realiza la paginación de las órdenes
                $datosMisOrdenes = $query->paginate(8);
                $cantidadOrdenes = $datosMisOrdenes->total();
                $noResultados = $cantidadOrdenes === 0;
    
                // Para exportar todas las órdenes sin aplicar el filtro de búsqueda,
                // se debe hacer la misma query básica.
                $paraExcel = Ot::where(function ($q) use ($datosTecnicoxUsuario) {
                    $q->where('cod_tecnico_encargado', $datosTecnicoxUsuario->id)
                      ->orWhereIn('numero_ot', function ($q2) use ($datosTecnicoxUsuario) {
                          $q2->select('cod_ot')
                             ->from('equipo_tecnico')
                             ->where('cod_tecnico', $datosTecnicoxUsuario->id);
                      });
                })->get();
    
                $vistaSeleccionada = $showAll === 'true' ? 'todas' : 'asignadas';
    
                return view('ordenespropiasxtecnico.misordenes', compact(
                    'datosMisOrdenes',
                    'cantidadOrdenes',
                    'paraExcel',
                    'vistaSeleccionada',
                    'noResultados',
                    'notificaciones'
                ));
            } else {
                return redirect()->route('error')->with('error', 'Técnico no encontrado.');
            }
        }
    
        return redirect()->route('login.submit');
    }


    
    public function crearActividadExtra($numero_ot)
    {
        // Validar si la OT existe
        $ot = Ot::where('numero_ot', $numero_ot)->first();
    
        // Si no existe la OT
        if (!$ot) {
            return redirect()->back()->with('error', 'Orden de trabajo no encontrada.');
        }
    
        // Pasar la OT a la vista
        return view('ordenespropiasxtecnico.actividad_extra', compact('numero_ot','ot'));
    }

    
    public function storeacti(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'nombre_actividad' => 'required|string|max:255',
            'horas_actividad' => 'required|numeric',
            'cod_ot' => 'required|exists:ot,numero_ot',
        ]);
    
        // Crear la nueva actividad extra
        ActividadExtra::create([
            'nombre_actividad' => $validated['nombre_actividad'],
            'horas_actividad' => $validated['horas_actividad'],
            'cod_ot' => $validated['cod_ot'],
        ]);
    
        // Redirigir con mensaje de éxito
        return back()->with('success', 'Actividad Extra creada exitosamente.');
    }
    
    public function updateacti(Request $request, $id)
    {
        // Validamos el dato que se envía. 
        // En este ejemplo actualizaremos solo el nombre de la actividad usando el input 'observacion'
        $validated = $request->validate([
            'observacion' => 'required|string|max:255'
        ],[
            'observacion.required' => 'El nombre de la actividad editada es obligatorio.',
            'observacion.max' => 'El nombre de la actividad editada no debe superar los 255 caracteres.'
        ]);
        
        
        // Buscamos la actividad extra según el ID
        $actividad = ActividadExtra::findOrFail($id);
    
        // Actualizamos el registro usando el valor validado
        $actividad->update([
            'nombre_actividad' => $validated['observacion']
        ]);
    
        // Redirigimos de vuelta con un mensaje de éxito
        return back()->with('success', 'Actividad Extra actualizada exitosamente.');
    }
    
    public function destroyacti($id)
    {
        // Buscamos la actividad extra por su ID; si no existe, lanza un error 404.
        $actividad = ActividadExtra::findOrFail($id);
    
        // Eliminamos la actividad extra.
        $actividad->delete();
    
        // Redirigimos de vuelta con un mensaje de éxito.
        return back()->with('success', 'Actividad Extra eliminada exitosamente.');
    }


    public function mostrarFinalizarOt($numero_ot)
{
    // Cargo la OT con sus dispositivos
    $orden = Ot::with(['estado', 'prioridad', 'DispositivoOT'])
               ->where('numero_ot', $numero_ot)
               ->firstOrFail();
    
    // Armo la estructura de dispositivos + sus tareas
    $dispositivos = $orden->DispositivoOT->map(function($dispOt) use ($orden) { // Aquí se pasa $orden
        // Primero renueva la entidad Dispositivo para sacar serie y modelo
        $disp = Dispositivo::find($dispOt->cod_dispositivo);
        $serie  = $disp ? $disp->numero_serie_dispositivo : 'N/D';
        $modelo = $disp && $disp->modelo
                   ? $disp->modelo->nombre_modelo
                   : 'Modelo no disponible';
        
        // Ahora las tareas de este dispositivo
        $tareas = TareaDispositivo::where('cod_dispositivo_ot', $dispOt->id)
            ->with('tarea')  // eager load de la relación TareaDispositivo → Tarea
            ->get()
            ->map(function($td) use ($orden) {
                $servicioTarea = $td->tarea->servicios()->where('cod_servicio', $orden->cod_servicio)->first();
                // Si no se encuentra una relación o el tiempo es nulo, se asigna 0
                $tiempoTarea = $servicioTarea ? ($servicioTarea->pivot->tiempo ?? 0) : 0;
    
                return [
                    'id'     => $td->tarea->id,
                    'nombre' => $td->tarea->nombre_tarea,
                    'tiempo' => $tiempoTarea,  // se usa directamente $tiempoTarea
                    'requiere_obs' => $td->tarea->requiere_obs,
                ];
            });
    
        return [
            'numero_serie' => $serie,
            'modelo'       => $modelo,
            'tareas'       => $tareas->toArray(),
        ];
    })->toArray();
    
    // Retorno la vista incluyendo la variable $dispositivos
    return view('ordenespropiasxtecnico.finalizar_ot', compact('orden', 'dispositivos'));
}

public function finalizarOt(Request $request, $numero_ot)
{
    $validated = $request->validate([
        'comentario_avance' => 'required|string|max:255',
        'fecha_avance' => 'required|date',
        'tareas' => 'array', // Asegúrate de que las tareas sean un array
    ]);

    // Aquí puedes manejar la lógica para finalizar la OT y marcar las tareas como completadas
    // ...

    return redirect()->route('misOrdenes')->with('success', 'OT finalizada exitosamente.');
}
}