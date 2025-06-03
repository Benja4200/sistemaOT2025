<?php

namespace App\Http\Controllers\ControladorOrdenes;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use App\Models\Ot;
use App\Models\Tarea;

use App\Models\TipoOt;
use App\Models\PrioridadOt;
use App\Models\EstadoOt;
use App\Models\TipoVisita;
use App\Models\Tecnico;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Sucursal;
use App\Models\Modelo;
use App\Models\Repuesto;
use App\Models\DispositivoOt;
use App\Models\RepuestoDispositivoOt;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Usuario; 
use App\Notifications\NuevaOrdenNotification;
// use Illuminate\Support\Facades\Validator;

class OrdenesController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener el número de resultados por página de la solicitud, con un valor por defecto (ej. 10)
        $perPage = $request->input('perPage', 10);
        // Validar que $perPage sea uno de los valores permitidos para seguridad y consistencia
        // Si no es un valor válido, se restablece a 10.
        // En tu metodo index de OrdenController.php:

        // Asegurarse de que perPage sea un número entero positivo. Si no lo es, usar 10.
        $perPage = filter_var($request->input('perPage', 10), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10; // Si filter_var devuelve false (no es entero positivo), usar 10

            // Opcional: Establecer un límite máximo para evitar cargas excesivas de datos
        $perPage = min($perPage, 200); // Por ejemplo, no más de 200 registros por página

        // 2. Obtener el valor del parámetro 'show_all' de la solicitud.
        //    Por defecto es 'true' (para mostrar todas las órdenes si el usuario tiene permiso).
        $showAll = $request->input('show_all', 'true');

        // 3. Obtener el usuario autenticado y verificar sus permisos.
        $usuario = Auth::user();
        // Asumo que 'ordenes.toggle_view' es el permiso para ver "todas las órdenes"
        $puedeCambiarVista = $usuario->can('ordenes.toggle_view');

        // 4. Inicializar el objeto 'tecnico' (si el usuario autenticado es un técnico)
        $tecnico = null;
        if ($usuario && $usuario->tecnico) {
            $tecnico = $usuario->tecnico;
        }

        // 5. Construir la consulta base para las órdenes (OTs)
        //    Se cargan las relaciones necesarias para mostrar los datos en la vista.
        $query = Ot::with([
            'contacto',
            'servicio',
            'tecnicoEncargado', // Nombre de la relación en tu modelo Ot para el técnico asignado
            'estado',
            'prioridad',
            'tipo',
            'tipoVisita',
            'contactoOt'
        ])->orderBy('created_at', 'desc'); // Ordenar por fecha de creación descendente por defecto

        // 6. Aplicar lógica de búsqueda si el campo 'search' está presente en la solicitud
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero_ot', 'like', "%{$search}%")
                 // Buscar por nombre de cliente a través de la cadena de relaciones
                ->orWhereHas('contactoOt.contacto.sucursal.cliente', function ($q_cliente) use ($search) {
                    $q_cliente->where('nombre_cliente', 'like', "%{$search}%");
                })
                // Buscar por nombre de sucursal a través de la cadena de relaciones
                ->orWhereHas('contactoOt.contacto.sucursal', function ($q_sucursal) use ($search) {
                    $q_sucursal->where('direccion_sucursal', 'like', "%{$search}%");
                });
            });
        }

        // 7. Aplicar lógica de filtrado de órdenes según el rol del usuario y la selección 'show_all'
        if ($tecnico) { // Si el usuario autenticado tiene un registro de técnico asociado
            // Si $showAll es 'true' Y el usuario tiene permiso para cambiar la vista (ver todas)
            if ($showAll === 'true' && $puedeCambiarVista) {
                // No se aplica ningún filtro adicional por técnico. La query ya trae todas.
            } else {
                // Si $showAll es 'false' (o no tiene permiso para ver todas),
                // se filtran las órdenes para mostrar solo las asignadas a este técnico.
                $query->where('cod_tecnico_encargado', $tecnico->id);
            }
        } else { // Si el usuario autenticado NO tiene un registro de técnico asociado
            // Si el usuario NO tiene permiso para cambiar la vista (ej. es un admin o usuario sin tecnico)
            // O si $showAll es 'false' (aunque no tenga tecnico, podría haber seleccionado esto)
            if (!$puedeCambiarVista || $showAll === 'false') {
                // Para usuarios sin rol de técnico o sin permiso de ver todo,
                // y que no están explícitamente viendo "todas",
                // se asegura que la consulta no devuelva resultados.
                $query->whereRaw('1 = 0'); // Esta condición siempre es falsa, resultando en una colección vacía
            }
            // Si el usuario no es técnico pero SÍ tiene permiso ($puedeCambiarVista) y $showAll es 'true',
            // la consulta base ($query) ya está configurada para mostrar todas las órdenes sin filtro.
        }

        // 8. Paginación final de los resultados de la consulta.
        //    'appends($request->except('page'))' es crucial: añade todos los demás parámetros de la URL
        //    (como 'search', 'perPage', 'show_all') a los enlaces de paginación.
        //    Esto asegura que los filtros se mantengan cuando el usuario navega entre las páginas.
        $ordenes = $query->paginate($perPage)->appends($request->except('page'));

        // 9. Determinar el estado de la vista seleccionada para que el radio button correcto
        //    esté marcado en el formulario de la vista.
        $vistaSeleccionada = ($showAll === 'true' && $puedeCambiarVista) ? 'todas' : 'asignadas';
        // Si no tiene permiso, siempre se considera 'asignadas' visualmente, aunque se muestren todas.

        // 10. Retornar la vista con todas las variables necesarias.
        return view('ordenes.ordenes', compact('ordenes', 'vistaSeleccionada', 'tecnico', 'puedeCambiarVista', 'perPage'));
    }

    public function obtenerOrden($id)
    {
        $orden = Ot::with([
            'contacto',
            'contacto.sucursal',
            'contacto.sucursal.cliente',
            'servicio',
            'tecnicoEncargado',
            'estado',
            'prioridad',
            'tipo',
            'tipoVisita',
            'contactoOt',
            'contactoOt.contacto',
            'contactoOt.contacto.sucursal',
            'contactoOt.contacto.sucursal.cliente',
            'tareasOt',
            'dispositivoOt',
            'dispositivoOt.detalles',
            'dispositivoOt.accesorios',
            'dispositivoOt.tareaDispositivo',
            'equipoTecnico'
        ])
            ->findOrFail($id);

        return response()->json($orden);
    }
    public function create()
    {
        $tipos = TipoOt::all();
        $prioridades = PrioridadOt::all();
        $estados = EstadoOt::all();
        $tiposVisitas = TipoVisita::all();
        $tecnicos = Tecnico::all();
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $sucursales = Sucursal::all();
        $modelodispositivos = Modelo::all();
        $ejecutivos = Usuario::whereHas('roles', function ($query) {
            $query->where('role_id', 22); // Filtra por el ID del rol Ejecutivo
        })->get();
        
        return view('ordenes.agregar', compact('tipos', 'prioridades', 'estados', 'tiposVisitas', 'tecnicos', 'clientes', 'servicios', 'sucursales', 'modelodispositivos','ejecutivos'));
    }

    public function obtenerDatosServicio(Request $request)
    {
        // obtener servicio por ID
        $servicio = Servicio::with('tipoServicio') // Relación con tipoServicio
            ->find($request->id_servicio); // Buscar servicio por id

        // verificar si el servicio existe
        if (!$servicio) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }

        // obtener la descripción del tipo de servicio
        $tipoServicioDescripcion = $servicio->tipoServicio->descripcion_tipo_servicio;

        // filtramos si el tipo de servicio es 1 o 2 (Requiere dispositivo)
        if (in_array($servicio->cod_tipo_servicio, [1, 2])) {

            // obtener dispositivos asociados al servicio, solo si requiere dispositivo
            $dispositivos = Dispositivo::with('modelo') // Obtener la relación modelo
                ->whereNotNull('cod_modelo') // Filtrar dispositivos con modelo
                ->get(['numero_serie_dispositivo', 'cod_modelo']); // Obtener los campos

            // respuesta con los dispositivos y la descripción del tipo de servicio
            return response()->json([
                'tipo_servicio' => $tipoServicioDescripcion,
                'dispositivos' => $dispositivos
            ]);
        }

        // si no requiere dispositivo, devolver los datos de tipo de servicio
        return response()->json([
            'tipo_servicio' => $tipoServicioDescripcion,
            'dispositivos' => null
        ]);
    }



    public function getServiciosJson()
    {
        $servicios = Servicio::all();

        return response()->json($servicios);
    }


    public function edit($id)
    {
        $tipos = TipoOt::all();
        $prioridades = PrioridadOt::all();
        $estados = EstadoOt::all();
        $tiposVisitas = TipoVisita::all();
        $tecnicos = Tecnico::all();
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $ejecutivos = Usuario::whereHas('roles', function ($query) {
            $query->where('role_id', 22); // Filtra por el ID del rol Ejecutivo
        })->get();
        
        $orden = Ot::with([
            'contacto',
            'contacto.sucursal',
            'contacto.sucursal.cliente',
            'servicio',
            'tecnicoEncargado',
            'estado',
            'prioridad',
            'tipo',
            'tipoVisita',
            'contactoOt',
            'contactoOt.contacto',
            'contactoOt.contacto.sucursal',
            'contactoOt.contacto.sucursal.cliente',
            'tareasOt',
            'dispositivoOt',
            'dispositivoOt.detalles',
            'dispositivoOt.accesorios',
            'dispositivoOt.tareaDispositivo',
            'equipoTecnico'
        ])
            ->findOrFail($id);


        return view('ordenes.editar', compact('tipos', 'prioridades', 'estados', 'tiposVisitas', 'tecnicos', 'clientes', 'servicios', 'orden','ejecutivos'));
    }

    public function store(Request $request)
    {
        $contador = $request->input('contadorBloques');

        $validator = Validator::make($request->all(), [
            'descripcion' => ['required', 'max:1000'],
            'ejecutivo' => ['required', 'exists:usuario,id', 'numeric'],
            'cliente' => ['required', 'exists:cliente,id', 'numeric'],
            'sucursal' => ['required', 'exists:sucursal,id', 'numeric'],
            'contactos' => [
                'required',
                function ($attribute, $value, $fail) {
                    foreach ($value as $contacto) {
                        if (!Contacto::find($contacto)) {
                            $fail("El contacto con id $contacto no existe.");
                        }
                    }
                }
            ],
            'servicio' => ['required', 'exists:servicio,id', 'numeric'],
            'contadorBloques' => ['required', 'numeric'],
            'tipoServicio' => 'required',
            'tecnicoEncargado' => ['required', 'exists:tecnico,id', 'numeric'],
            'tecnicos' => [
                'required',
                function ($attribute, $value, $fail) {
                    foreach ($value as $tecnico) {
                        if (!Tecnico::find($tecnico)) {
                            $fail("El tecnico con id $tecnico no existe.");
                        }
                    }
                }
            ],
            
            'prioridad' => ['required', 'exists:prioridad_ot,id', 'numeric'],
            'tipo' => ['required', 'exists:tipo_ot,id', 'numeric'],
            'tipoVisita' => ['required', 'exists:tipo_visita,id', 'numeric'],
            'fecha' => ['required', 'date'],
            'cotizacion' => ['nullable', 'max:50'],
            'tareasSinD' => ['nullable', 'array'],
            'dispositivos' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    foreach ($value as $dispositivo) {
                        if (!Dispositivo::find($dispositivo)) {
                            $fail("El dispositivo con id $dispositivo no existe.");
                        }
                    }
                }
            ],

        ]);

        for ($i = 0; $i < $contador; $i++) {
            $validator->sometimes('tareasDispositivos-' . $i, 'nullable', function ($input) use ($i) {

                return $input->has('tareasDispositivos-' . $i);
            });
        }

        for ($i = 0; $i < $contador; $i++) {
            $validator->sometimes('detallesDispositivo-' . $i, 'nullable', function ($input) use ($i) {

                return $input->has('detallesDispositivo-' . $i);
            });
        }
        for ($i = 0; $i < $contador; $i++) {
            $validator->sometimes('accesoriosDispositivo-' . $i, 'nullable', function ($input) use ($i) {

                return $input->has('accesoriosDispositivo-' . $i);
            });
        }

        $datosValidados = $validator->validated();

        $tiempoEnMinutos = 0;
        $tiempoEnHoras = 0;
        if ($datosValidados['tipoServicio'] == 1) {
            foreach ($datosValidados['tareasSinD'] as $tarea) {
                
                $servicio = Servicio::find($datosValidados['servicio']);
                        
                if ($servicio) {
                    // Obtener el tiempo de la tarea específica en la tabla pivot
                    $tiempoTarea = $servicio->tareasServicio()->where('tarea.id', $tarea)->first()->pivot->tiempo ?? 0;
                    // Acumular el tiempo
                    $tiempoEnMinutos += $tiempoTarea;
                }
                
                
                //$tiempoTarea = Tarea::find($tarea)->tiempo_tarea;
                //$tiempoEnMinutos += $tiempoTarea;
            }
        } elseif ($datosValidados['tipoServicio'] == 2) {
            for ($i = 0; $i < $datosValidados['contadorBloques']; $i++) {
                if (isset($datosValidados['tareasDispositivos-' . $i])) {
                    $skipFirst = true;
                    foreach ($datosValidados['tareasDispositivos-' . $i] as $tarea) {
                        if ($skipFirst) {
                            $skipFirst = false;
                            continue;
                        }
                        
                        $servicio = Servicio::find($datosValidados['servicio']);
                        
                        if ($servicio) {
                            // Obtener el tiempo de la tarea específica en la tabla pivot
                            $tiempoTarea = $servicio->tareasServicio()->where('tarea.id', $tarea)->first()->pivot->tiempo ?? 0;
                            // Acumular el tiempo
                            $tiempoEnMinutos += $tiempoTarea;
                        }
                        
                        //$tiempoTarea = $servicio->tareas()->where('id', $tarea)->first()->pivot->tiempo ?? 0;
                        //$tiempoTarea = Tarea::find($tarea)->tiempo_tarea;
                        //$tiempoEnMinutos += $tiempoTarea;
                    }
                }
            }
        }
    
        $tiempoEnHoras = ceil($tiempoEnMinutos / 60);
       
        //6 HORAS DIARIAS
        $diasTrabajo = ceil($tiempoEnHoras / 6);

        $fecha_inicio = $datosValidados['fecha'];

        try {
            $selectedDate = new DateTime($fecha_inicio);
        } catch (Exception $e) {
            echo ('La fecha seleccionada no es válidas.');
            return;
        }

        try {
            // Obtener días feriados desde la API
            $feriados = self::obtener_feriados_chile();

            // Calcular la fecha estimada de fin de la OT
            $fecha_fin_estimada = self::add_business_days($selectedDate, $diasTrabajo, $feriados);
            $fecha_fin_estimada = $fecha_fin_estimada->format('Y-m-d');

            $ot = new Ot();
            $ot->tiempo_ot = $tiempoEnMinutos;
            $ot->horas_ot = $tiempoEnHoras;
            $ot->descripcion_ot = $datosValidados['descripcion'];
            $ot->cotizacion = $datosValidados['cotizacion'];
            $ot->cod_tipo_ot = $datosValidados['tipo'];
            $ot->cod_prioridad_ot = $datosValidados['prioridad'];
            //$ot->cod_estado_ot = $datosValidados['estado'];
            $ot->cod_tipo_visita = $datosValidados['tipoVisita'];
            $ot->cod_servicio = $datosValidados['servicio'];
            //$ot->cod_contacto = 114; // lmao
            $ot->cod_tecnico_encargado = $datosValidados['tecnicoEncargado'];
            $ot->cod_ejecutivo = $datosValidados['ejecutivo'];
            $ot->fecha_inicio_planificada_ot = $datosValidados['fecha'];
            $ot->fecha_fin_planificada_ot = $fecha_fin_estimada;
            $ot->save();

            $idOt = $ot->id;
            foreach ($datosValidados['contactos'] as $contacto) {
                $ot->contactoOt()->create([
                    'cod_ot' => $idOt,
                    'cod_contacto' => $contacto,
                ]);
            }
            foreach ($datosValidados['tecnicos'] as $tecnico) {
                $ot->EquipoTecnico()->create([
                    'cod_ot' => $idOt,
                    'cod_tecnico' => $tecnico,
                ]);
            }

            if ($datosValidados['tipoServicio'] == 1) {

                foreach ($datosValidados['tareasSinD'] as $tarea) {
                    $ot->TareasOt()->create([
                        'cod_ot' => $idOt,
                        'cod_tarea' => $tarea,
                    ]);
                }
            } elseif ($datosValidados['tipoServicio'] == 2) {

                foreach ($datosValidados['dispositivos'] as $dispositivo) {
                    $dispositivoOt = $ot->DispositivoOT()->create([
                        'cod_ot' => $idOt,
                        'cod_dispositivo' => $dispositivo,
                    ]);

                    for ($i = 0; $i < $datosValidados['contadorBloques']; $i++) {
                        if (isset($datosValidados['tareasDispositivos-' . $i])) {
                            if ($datosValidados['tareasDispositivos-' . $i][0] == $dispositivo) {
                                foreach ($datosValidados['tareasDispositivos-' . $i] as $key => $tarea) {
                                    if ($key > 0) { // Evita excluir IDs de tarea que coincidan con el dispositivo
                                        $dispositivoOt->tareaDispositivo()->create([
                                            'cod_dispositivo_ot' => $dispositivoOt->id,
                                            'cod_tarea' => $tarea,
                                        ]);
                                    }
                                }
                            }
                        }

                        if (isset($datosValidados['detallesDispositivo-' . $i])) {
                            if ($datosValidados['detallesDispositivo-' . $i]['existe'] == 1) {
                                if ($datosValidados['detallesDispositivo-' . $i]['dispositivo'] == $dispositivo) {
                                    $dispositivoOt->detalles()->create([
                                        'rayones_det' => $datosValidados['detallesDispositivo-' . $i]['rayones'],
                                        'rupturas_det' => $datosValidados['detallesDispositivo-' . $i]['rupturas'],
                                        'tornillos_det' => $datosValidados['detallesDispositivo-' . $i]['tornillos'],
                                        'gomas_det' => $datosValidados['detallesDispositivo-' . $i]['gomas'],
                                        'estado_dispositivo_det' => $datosValidados['detallesDispositivo-' . $i]['estado'],
                                        'observaciones_det' => $datosValidados['detallesDispositivo-' . $i]['observaciones'],
                                        'cod_dispositivo_ot' => $dispositivoOt->id,
                                    ]);
                                }
                            }
                        }

                        if (isset($datosValidados['accesoriosDispositivo-' . $i])) {
                            if ($datosValidados['accesoriosDispositivo-' . $i]['existe'] == 1) {
                                if ($datosValidados['accesoriosDispositivo-' . $i]['dispositivo'] == $dispositivo) {
                                    $dispositivoOt->accesorios()->create([
                                        'cargador_acc' => $datosValidados['accesoriosDispositivo-' . $i]['cargador'],
                                        'cargador_posee_acc' => $datosValidados['accesoriosDispositivo-' . $i]['cargadorSeleccionado'],
                                        'cable_acc' => $datosValidados['accesoriosDispositivo-' . $i]['cablePoder'],
                                        'adaptador_acc' => $datosValidados['accesoriosDispositivo-' . $i]['adaptadorPoder'],
                                        'bateria_acc' => $datosValidados['accesoriosDispositivo-' . $i]['bateria'],
                                        'bateria_posee_acc' => $datosValidados['accesoriosDispositivo-' . $i]['bateriaSeleccionada'],
                                        'pantalla_acc' => $datosValidados['accesoriosDispositivo-' . $i]['pantalla'],
                                        'teclado_acc' => $datosValidados['accesoriosDispositivo-' . $i]['teclado'],
                                        'drum_acc' => $datosValidados['accesoriosDispositivo-' . $i]['drum'],
                                        'toner_acc' => $datosValidados['accesoriosDispositivo-' . $i]['toner'],
                                        'cod_dispositivo_ot' => $dispositivoOt->id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            
            // Notificar al técnico encargado
            $responsableTec = Tecnico::find($ot->cod_tecnico_encargado);
            $notificados = []; // Array para almacenar los IDs ya notificados
            
            if ($responsableTec && $responsableTec->usuario) {
                // Notificamos al usuario del técnico encargado
                $responsableTec->usuario->notify(new NuevaOrdenNotification($ot));
                $notificados[] = $responsableTec->id;
            }
            
            // Obtener los tecnicos que pertenecen al equipo de trabajo para esta OT
            $equipoTecnicoIds = DB::table('equipo_tecnico')
                ->where('cod_ot', $ot->numero_ot)
                ->pluck('cod_tecnico')
                ->toArray();
            
            // Notificar a cada técnico del equipo solo si aún no fue notificado
            foreach ($equipoTecnicoIds as $tecnicoId) {
                if (!in_array($tecnicoId, $notificados)) {
                    $tecnicoEquipo = Tecnico::find($tecnicoId);
                    if ($tecnicoEquipo && $tecnicoEquipo->usuario) {
                        $tecnicoEquipo->usuario->notify(new NuevaOrdenNotification($ot));
                        $notificados[] = $tecnicoId;
                    }
                }
            }

    
            return response()->json(['message' => 'Orden de trabajo creada correctamente!'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear la orden de trabajo ', 'errors' => $validator->errors()], 422);
            // return redirect()->back()->withErrors($validator)->withInput();
            // return redirect()->route('ordenes.index')->with('error', 'Error al crear la orden de trabajo.');
        }
    }

    public function update(Request $request, $id)
    {
        //dd($request);
        $contador = $request->input('contadorBloques');

        $validator = Validator::make($request->all(), [
            'descripcion' => ['required', 'max:1000'],
            'ejecutivo' => ['required', 'exists:usuario,id', 'numeric'],
            'cliente' => ['required', 'exists:cliente,id', 'numeric'],
            'sucursal' => ['required', 'exists:sucursal,id', 'numeric'],
            'contactos' => [
                'required',
                function ($attribute, $value, $fail) {
                    foreach ($value as $contacto) {
                        if (!Contacto::find($contacto)) {
                            $fail("El contacto con id $contacto no existe.");
                        }
                    }
                }
            ],
            'servicio' => ['required', 'exists:servicio,id', 'numeric'],
            'contadorBloques' => ['required', 'numeric'],
            'tipoServicio' => 'required',
            'tecnicoEncargado' => ['required', 'exists:tecnico,id', 'numeric'],
            'tecnicos' => [
                'required',
                function ($attribute, $value, $fail) {
                    foreach ($value as $tecnico) {
                        if (!Tecnico::find($tecnico)) {
                            $fail("El tecnico con id $tecnico no existe.");
                        }
                    }
                }
            ],
            'estado' => ['required', 'exists:estado_ot,id', 'numeric'],
            'prioridad' => ['required', 'exists:prioridad_ot,id', 'numeric'],
            'tipo' => ['required', 'exists:tipo_ot,id', 'numeric'],
            'tipoVisita' => ['required', 'exists:tipo_visita,id', 'numeric'],
            'fecha' => ['required', 'date'],
            'cotizacion' => ['nullable', 'max:50'],
            'tareasSinD' => ['nullable', 'array'],
            'dispositivos' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    foreach ($value as $dispositivo) {
                        if (!Dispositivo::find($dispositivo)) {
                            $fail("El dispositivo con id $dispositivo no existe.");
                        }
                    }
                }
            ],

        ]);

        for ($i = 0; $i < $contador; $i++) {
            $validator->sometimes('tareasDispositivos-' . $i, 'nullable', function ($input) use ($i) {

                return $input->has('tareasDispositivos-' . $i);
            });
        }

        for ($i = 0; $i < $contador; $i++) {
            $validator->sometimes('detallesDispositivo-' . $i, 'nullable', function ($input) use ($i) {

                return $input->has('detallesDispositivo-' . $i);
            });
        }
        for ($i = 0; $i < $contador; $i++) {
            $validator->sometimes('accesoriosDispositivo-' . $i, 'nullable', function ($input) use ($i) {

                return $input->has('accesoriosDispositivo-' . $i);
            });
        }

        $datosValidados = $validator->validated();

        $tiempoEnMinutos = 0;
        $tiempoEnHoras = 0;
        if ($datosValidados['tipoServicio'] == 1) {
            foreach ($datosValidados['tareasSinD'] as $tarea) {
                
                $servicio = Servicio::find($datosValidados['servicio']);
                        
                if ($servicio) {
                    // Obtener el tiempo de la tarea específica en la tabla pivot
                    $tiempoTarea = $servicio->tareasServicio()->where('tarea.id', $tarea)->first()->pivot->tiempo ?? 0;
                    // Acumular el tiempo
                    $tiempoEnMinutos += $tiempoTarea;
                }
                
                
                //$tiempoTarea = Tarea::find($tarea)->tiempo_tarea;
                //$tiempoEnMinutos += $tiempoTarea;
            }
        } elseif ($datosValidados['tipoServicio'] == 2) {
            for ($i = 0; $i < $datosValidados['contadorBloques']; $i++) {
                if (isset($datosValidados['tareasDispositivos-' . $i])) {
                    $skipFirst = true;
                    foreach ($datosValidados['tareasDispositivos-' . $i] as $tarea) {
                        if ($skipFirst) {
                            $skipFirst = false;
                            continue;
                        }
                        
                        $servicio = Servicio::find($datosValidados['servicio']);
                        
                        if ($servicio) {
                            // Obtener el tiempo de la tarea específica en la tabla pivot
                            $tiempoTarea = $servicio->tareasServicio()->where('tarea.id', $tarea)->first()->pivot->tiempo ?? 0;
                            // Acumular el tiempo
                            $tiempoEnMinutos += $tiempoTarea;
                        }
                        //$tiempoTarea = Tarea::find($tarea)->tiempo_tarea;
                        //$tiempoEnMinutos += $tiempoTarea;
                    }
                }
            }
        }

        $tiempoEnHoras = ceil($tiempoEnMinutos / 60);

        //6 HORAS DIARIAS

        $diasTrabajo = ceil($tiempoEnHoras / 6);

        $fecha_inicio = $datosValidados['fecha'];

        try {
            $selectedDate = new DateTime($fecha_inicio);
        } catch (Exception $e) {
            echo ('La fecha seleccionada no es válidas.');
            return;
        }

        $ot = Ot::findOrFail($id);
        //dd($ot->DispositivoOt()->detalles());
        try {
            // Obtener días feriados desde la API
            $feriados = self::obtener_feriados_chile();

            // Calcular la fecha estimada de fin de la OT
            $fecha_fin_estimada = self::add_business_days($selectedDate, $diasTrabajo, $feriados);
            $fecha_fin_estimada = $fecha_fin_estimada->format('Y-m-d');


            $ot->tiempo_ot = $tiempoEnMinutos;
            $ot->horas_ot = $tiempoEnHoras;
            $ot->descripcion_ot = $datosValidados['descripcion'];
            $ot->cotizacion = $datosValidados['cotizacion'];
            $ot->cod_tipo_ot = $datosValidados['tipo'];
            $ot->cod_prioridad_ot = $datosValidados['prioridad'];
            $ot->cod_estado_ot = $datosValidados['estado'];
            $ot->cod_tipo_visita = $datosValidados['tipoVisita'];
            $ot->cod_servicio = $datosValidados['servicio'];
            //$ot->cod_contacto = 114; // lmao
            $ot->cod_ejecutivo = $datosValidados['ejecutivo'];
            $ot->cod_tecnico_encargado = $datosValidados['tecnicoEncargado'];
            $ot->fecha_inicio_planificada_ot = $datosValidados['fecha'];
            $ot->fecha_fin_planificada_ot = $fecha_fin_estimada;
            $ot->save();

            $ot->contactoOt()->delete();
            $ot->EquipoTecnico()->delete();
            $ot->TareasOt()->delete();
            //$ot->DispositivoOt()->detalles->delete();
            //$ot->DispositivoOt()->accesorios->delete();
            //$ot->DispositivoOt()->tareaDispositivo->delete();
            
            $ot->DispositivoOt()->delete();

            foreach ($datosValidados['contactos'] as $contacto) {
                $ot->contactoOt()->create([
                    'cod_ot' => $id,
                    'cod_contacto' => $contacto,
                ]);
            }
            foreach ($datosValidados['tecnicos'] as $tecnico) {
                $ot->EquipoTecnico()->create([
                    'cod_ot' => $id,
                    'cod_tecnico' => $tecnico,
                ]);
            }

            if ($datosValidados['tipoServicio'] == 1) {

                foreach ($datosValidados['tareasSinD'] as $tarea) {
                    $ot->TareasOt()->create([
                        'cod_ot' => $id,
                        'cod_tarea' => $tarea,
                    ]);
                }
            } elseif ($datosValidados['tipoServicio'] == 2) {

                foreach ($datosValidados['dispositivos'] as $dispositivo) {
                    $dispositivoOt = $ot->DispositivoOT()->create([
                        'cod_ot' => $id,
                        'cod_dispositivo' => $dispositivo,
                    ]);

                    for ($i = 0; $i < $datosValidados['contadorBloques']; $i++) {
                        if (isset($datosValidados['tareasDispositivos-' . $i])) {
                            if ($datosValidados['tareasDispositivos-' . $i][0] == $dispositivo) {
                                foreach ($datosValidados['tareasDispositivos-' . $i] as $key => $tarea) {
                                    if ($key > 0) { // Evita excluir IDs de tarea que coincidan con el dispositivo
                                        $dispositivoOt->tareaDispositivo()->create([
                                            'cod_dispositivo_ot' => $dispositivoOt->id,
                                            'cod_tarea' => $tarea,
                                        ]);
                                    }
                                }

                            }
                        }

                        if (isset($datosValidados['detallesDispositivo-' . $i])) {
                            if ($datosValidados['detallesDispositivo-' . $i]['existe'] == 1) {
                                if ($datosValidados['detallesDispositivo-' . $i]['dispositivo'] == $dispositivo) {
                                    $dispositivoOt->detalles()->create([
                                        'rayones_det' => $datosValidados['detallesDispositivo-' . $i]['rayones'],
                                        'rupturas_det' => $datosValidados['detallesDispositivo-' . $i]['rupturas'],
                                        'tornillos_det' => $datosValidados['detallesDispositivo-' . $i]['tornillos'],
                                        'gomas_det' => $datosValidados['detallesDispositivo-' . $i]['gomas'],
                                        'estado_dispositivo_det' => $datosValidados['detallesDispositivo-' . $i]['estado'],
                                        'observaciones_det' => $datosValidados['detallesDispositivo-' . $i]['observaciones'],
                                        'cod_dispositivo_ot' => $dispositivoOt->id,
                                    ]);
                                }
                            }
                        }

                        if (isset($datosValidados['accesoriosDispositivo-' . $i])) {
                            if ($datosValidados['accesoriosDispositivo-' . $i]['existe'] == 1) {
                                if ($datosValidados['accesoriosDispositivo-' . $i]['dispositivo'] == $dispositivo) {
                                    $dispositivoOt->accesorios()->create([
                                        'cargador_acc' => $datosValidados['accesoriosDispositivo-' . $i]['cargador'],
                                        'cargador_posee_acc' => $datosValidados['accesoriosDispositivo-' . $i]['cargadorSeleccionado'],
                                        'cable_acc' => $datosValidados['accesoriosDispositivo-' . $i]['cablePoder'],
                                        'adaptador_acc' => $datosValidados['accesoriosDispositivo-' . $i]['adaptadorPoder'],
                                        'bateria_acc' => $datosValidados['accesoriosDispositivo-' . $i]['bateria'],
                                        'bateria_posee_acc' => $datosValidados['accesoriosDispositivo-' . $i]['bateriaSeleccionada'],
                                        'pantalla_acc' => $datosValidados['accesoriosDispositivo-' . $i]['pantalla'],
                                        'teclado_acc' => $datosValidados['accesoriosDispositivo-' . $i]['teclado'],
                                        'drum_acc' => $datosValidados['accesoriosDispositivo-' . $i]['drum'],
                                        'toner_acc' => $datosValidados['accesoriosDispositivo-' . $i]['toner'],
                                        'cod_dispositivo_ot' => $dispositivoOt->id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }


            return response()->json(['message' => 'Orden de trabajo actualizada correctamente!'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar la orden de trabajo ', 'errors' => $validator->errors()], 422);
            return;
        }
    }


    public static function obtener_feriados_chile()
    {
        $feriados = Cache::get('feriados_chilev2');

        if (!$feriados) {
            $response = Http::withOptions(['verify' => false])->get('https://apis.digital.gob.cl/fl/feriados/' . date("Y"));

            if ($response->status() != 200) {
                return []; // Si hay un error, retorna un array vacío
            }

            $feriados = $response->json(); // Decodifica la respuesta JSON

            // Verifica si hay un error en la respuesta
            if (isset($feriados['error']) && $feriados['error']) {
                \Log::warning("No se encontraron feriados: " . $feriados['message']);
                return []; // Retorna un array vacío si hay un error
            }

            if (empty($feriados)) {
                return [];
            }

            $feriado_fechas = array_map(function ($feriado) {
                return $feriado['fecha'];
            }, $feriados);

            // Puedes guardar los datos en una opción transitoria para cachear los resultados
            Cache::put('feriados_chilev2', $feriado_fechas, 86400); // 86400 is the number of seconds in a day
            return $feriado_fechas;
        }
        return $feriados;
    }


    function add_business_days($date, $days, $holidays)
    {
        $count = 0;
        $result = clone $date;

        while ($count < $days) {
            $result->modify('+1 day');
            $weekday = $result->format('N'); // 1 para Lunes, 7 para Domingo

            if ($weekday < 6 && !in_array($result->format('Y-m-d'), $holidays)) {
                $count++;
            }
        }

        return $result;
    }


    public function buscar(Request $request)
    {
        $search = $request->input('search');
        $showAll = $request->input('show_all', 'false');
    
        $usuario = Auth::user();
    
        $puedeCambiarVista = $usuario->can('ordenes.toggle_view');
    
        $query = Ot::with([
            'contacto',
            'contacto.sucursal',
            'contacto.sucursal.cliente',
            'servicio',
            'tecnicoEncargado',
            'estado',
            'prioridad',
            'tipo',
            'tipoVisita'
        ]);

        if (!$puedeCambiarVista && $usuario->tecnico) {
            $query->where('cod_tecnico_encargado', $usuario->tecnico->id);
        }

        if ($showAll === 'true') {
            if (is_numeric($search)) {
                
                $query->where('numero_ot', $search);
                
            } else {
                
                $query->where(function ($query) use ($search) {
                    $query->orWhere('descripcion_ot', 'like', "%$search%")
                        ->orWhere('cotizacion', 'like', "%$search%")
                        ->orWhereHas('contactoOt.contacto', function ($query) use ($search) { // Adjusted to contactOts to match your relations
                            $query->where('nombre_contacto', 'like', "%$search%")
                                ->orWhereHas('sucursal', function ($query) use ($search) {
                                    $query->where('direccion_sucursal', 'like', "%$search%")
                                        ->orWhereHas('cliente', function ($query) use ($search) {
                                            $query->where('nombre_cliente', 'like', "%$search%");
                                        });
                                });
                        })
                        ->orWhereHas('servicio', function ($query) use ($search) {
                            $query->where('nombre_servicio', 'like', "%$search%");
                        })
                        ->orWhereHas('tecnicoEncargado', function ($query) use ($search) {
                            $query->where('nombre_tecnico', 'like', "%$search%");
                        })
                        ->orWhereHas('estado', function ($query) use ($search) {
                            $query->where('descripcion_estado_ot', 'like', "%$search%");
                        })
                        ->orWhereHas('prioridad', function ($query) use ($search) {
                            $query->where('descripcion_prioridad_ot', 'like', "%$search%");
                        })
                        ->orWhereHas('tipo', function ($query) use ($search) {
                            $query->where('descripcion_tipo_ot', 'like', "%$search%");
                        })
                        ->orWhereHas('tipoVisita', function ($query) use ($search) {
                            $query->where('descripcion_tipo_visita', 'like', "%$search%");
                        })
                        ->orWhereHas('contactoOt', function ($query) use ($search) {
                            $query->whereHas('contacto', function ($query) use ($search) {
                                $query->where('nombre_contacto', 'like', "%$search%")
                                    ->orWhereHas('sucursal', function ($query) use ($search) {
                                        $query->where('direccion_sucursal', 'like', "%$search%")
                                            ->orWhereHas('cliente', function ($query) use ($search) {
                                                $query->where('nombre_cliente', 'like', "%$search%");
                                            });
                                    });
                            });
                        });
                });
            }
        } else {

            if (is_numeric($search)) {

                $query->where('numero_ot', $search);
                
            } else {

                $query->where(function ($query) use ($search) {
                    $query->orWhere('descripcion_ot', 'like', "%$search%")
                        ->orWhereHas('contactoOt.contacto', function ($query) use ($search) {
                            $query->where('nombre_contacto', 'like', "%$search%");
                        });
                });
            }
        }
    
        $ordenes = $query->orderBy('numero_ot', 'desc')->paginate(6);
    
        $vistaSeleccionada = $showAll === 'true' ? 'todas' : 'asignadas';
    
        return view('ordenes.ordenes', compact('ordenes', 'vistaSeleccionada', 'puedeCambiarVista'));
    }



    public function show($id)
    {
        
        $usuario = Auth::user();

        $orden = Ot::with([
            'contacto',
            'servicio',
            'tecnicoEncargado',
            'estado',
            'prioridad',
            'tipo',
            'tipoVisita',
            'contactoOt'
        ])->findOrFail($id);

        if (!$usuario->can('ordenes.toggle_view')) {
            
            if ($usuario->tecnico && $orden->cod_tecnico_encargado !== $usuario->tecnico->id) {
                
                abort(403, 'No tienes permiso para ver esta orden.');
            }
        }

        return view('ordenes.detalle', compact('orden'));
    }

    public function tareas($id)
    {
        $servicio = Servicio::findOrFail($id);
        $tareas = $servicio->tareasServicio;
       
        return response()->json($tareas);
    }

    public function sucursales($id)
    {
        $cliente = Cliente::findOrFail($id);
        $sucursales = $cliente->sucursal;

        return response()->json($sucursales);
    }

    public function contactos($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $contactos = $sucursal->contacto;

        return response()->json($contactos);
    }

    public function dispositivos($idSucursal, $idServicio)
    {
        $servicio = Servicio::findOrFail($idServicio);
        if ($servicio->cod_tipo_servicio != 2) {
            return response()->json([]);
        }
    
        $sucursal = Sucursal::findOrFail($idSucursal);
        
        // Obtener la categoría del servicio
        $categoriasServicio = $servicio->categoriasEquipos; // Asegúrate de que esta relación esté definida
    
        // Obtener dispositivos de la sucursal que pertenecen a la categoría del servicio
        $dispositivos = $sucursal->dispositivo()
            ->with('modelo', 'modelo.sublinea.linea.subcategoria.categoria')
            ->whereHas('modelo.sublinea.linea.subcategoria.categoria', function ($query) use ($categoriasServicio) {
                $query->whereIn('id', $categoriasServicio->pluck('id'));
            })
            ->get();
    
        // Obtener sublíneas únicas de los dispositivos, excluyendo las nulas
        $sublineas = $dispositivos->pluck('modelo.sublinea')->filter(function ($sublinea) {
            return $sublinea !== null; // Filtrar sublíneas nulas
        })->unique('id')->values();
    
        return response()->json([
            'dispositivos' => $dispositivos,
            'sublineas' => $sublineas,
        ]);
    }

    public function servicioTipo($id)
    {
        $servicio = Servicio::findOrFail($id);

        return response()->json($servicio->only('cod_tipo_servicio'));
    }

    public function tecnicosServicio($id)
    {
        $tecnicos = DB::table('tecnico')
            ->join('tecnico_servicio', 'tecnico.id', '=', 'tecnico_servicio.cod_tecnico')
            ->where('tecnico_servicio.cod_servicio', $id)
            ->whereNull('tecnico.deleted_at') // Filtra técnicos no eliminados
            ->distinct()
            ->select('tecnico.*', 
                // Cantidad de órdenes donde el técnico es el encargado y no eliminadas
                DB::raw('(SELECT COUNT(*) FROM ot WHERE ot.cod_tecnico_encargado = tecnico.id AND ot.cod_estado_ot != 3 AND ot.deleted_at IS NULL) AS cantidad_ordenes_encargado'),
                
                // Cantidad de órdenes donde el técnico está en el equipo, pero no es el encargado y no eliminadas
                DB::raw('(SELECT COUNT(*) FROM equipo_tecnico 
                          JOIN ot ON equipo_tecnico.cod_ot = ot.numero_ot
                          WHERE equipo_tecnico.cod_tecnico = tecnico.id 
                          AND ot.cod_tecnico_encargado != tecnico.id
                          AND ot.cod_estado_ot != 3
                          AND ot.deleted_at IS NULL
                          AND equipo_tecnico.deleted_at IS NULL) AS cantidad_ordenes_equipo'),
    
                // Suma de horas de las órdenes donde el técnico es encargado y no eliminadas
                DB::raw('(SELECT COALESCE(SUM(ot.horas_ot), 0) FROM ot WHERE ot.cod_tecnico_encargado = tecnico.id AND ot.cod_estado_ot != 3 AND ot.deleted_at IS NULL) AS suma_horas_encargado'),
    
                // Suma de horas de las órdenes donde el técnico está en el equipo, pero no es el encargado y no eliminadas
                DB::raw('(SELECT COALESCE(SUM(ot.horas_ot), 0) FROM equipo_tecnico 
                          JOIN ot ON equipo_tecnico.cod_ot = ot.numero_ot
                          WHERE equipo_tecnico.cod_tecnico = tecnico.id 
                          AND ot.cod_tecnico_encargado != tecnico.id
                          AND ot.cod_estado_ot != 3
                          AND ot.deleted_at IS NULL
                          AND equipo_tecnico.deleted_at IS NULL) AS suma_horas_equipo')
            )
            ->get();
        //dd($tecnicos);
        return response()->json($tecnicos);
    }





    
    public function destroy($id)
    {
        
        $ordenselect = Ot::findOrFail($id);

        $ordenselect->delete();

        return back()->with('delete', 'Ot eliminada exitosamente. Serás redirigido en unos segundos.');
    }
    
    public function verRepuestosOrden($id)
    {
        $orden = Ot::findOrFail($id);
    
        if ($orden->DispositivoOT()->exists()) {
            //dd($orden->DispositivoOT[0]->dispositivo->modelo->repuestos);
            return view('ordenespropiasxtecnico.repuestosOrden', compact('orden'));
            
        } else {
            return back()->with('error', 'Esta orden no posee repuestos, ya que no posee equipos.');
        }
    }
    
    public function asignarRepuestoOrden(Request $request)
    {
        // Validar los datos antes de procesarlos
        $request->validate([
            'dispositivoOT_id' => 'required|integer|exists:dispositivo_ot,id',
            'repuesto_id' => 'required|integer|exists:repuesto,id',
            'detalle_asignacion' => 'required|string|max:1000'
        ], [
            'dispositivoOT_id.required' => 'El ID del dispositivo es obligatorio.',
            'dispositivoOT_id.integer' => 'El ID del dispositivo debe ser un número entero.',
            'dispositivoOT_id.exists' => 'El dispositivo seleccionado no existe en la base de datos.',
    
            'repuesto_id.required' => 'El ID del repuesto es obligatorio.',
            'repuesto_id.integer' => 'El ID del repuesto debe ser un número entero.',
            'repuesto_id.exists' => 'El repuesto seleccionado no existe en la base de datos.',
    
            'detalle_asignacion.required' => 'La observación es obligatoria.',
            'detalle_asignacion.string' => 'La observación debe ser un texto válido.',
            'detalle_asignacion.max' => 'La observación no debe superar los 1000 caracteres.'
        ]);
    
        // Buscar los registros en la base de datos
        $dispositivoOT = DispositivoOt::find($request->dispositivoOT_id);
        $repuesto = Repuesto::find($request->repuesto_id);
    
        // Crear el registro en la tabla de relación
        $repuestoDispositivo = RepuestoDispositivoOt::create([
            'observacion_repuesto'  => $request->detalle_asignacion, 
            'cod_repuesto'        => $repuesto->id,
            'cod_dispositivo_ot'  => $dispositivoOT->id,
        ]);
    
        return redirect()->back()->with('success', 'Repuesto asignado correctamente.');
    }

    
    public function editarRepuestoOrden(Request $request, $id)
    {
        // Validación del request con mensajes personalizados
        $request->validate([
            'observacion' => 'required|string|max:1000'
        ], [
            'observacion.required' => 'La observación editada no puede estar vacía.',
            'observacion.string' => 'La observación editada debe ser un texto válido.',
            'observacion.max' => 'La observación editada no debe superar los 1000 caracteres.'
        ]);
    
        // Buscar el registro
        $detalle = RepuestoDispositivoOt::find($id);
    
        // Validar que exista
        if (!$detalle) {
            return redirect()->back()->with('error', 'No se encontró el repuesto asignado.');
        }
    
        // Actualizar la observación
        $detalle->update([
            'observacion_repuesto' => $request->observacion
        ]);
    
        return redirect()->back()->with('success', 'La observación se ha actualizado correctamente.');
    }




    
    public function eliminarRepuestoOrden(Request $request, $id)
    {
        // Buscar el registro en la tabla detalle_repuesto_dispositivo_ot
        $detalle = RepuestoDispositivoOt::find($id);
    
        // Validar que exista
        if (!$detalle) {
            return redirect()->back()->with('error', 'No se encontró el repuesto asignado a la orden.');
        }
    
        // Eliminar el registro
        $detalle->delete();
    
        return redirect()->back()->with('success', 'Repuesto eliminado de la orden correctamente.');
    }



}
