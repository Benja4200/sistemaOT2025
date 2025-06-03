@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/detallesOT.css') }}">
@endsection
@section('content')

<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2>Detalle de la Orden {{ $orden->numero_ot }}</h2>
            <a href="{{ route('ordenes.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
            </a>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="infoExtra">

                    @php
                    $prioridadClass = '';

                    switch ($orden->prioridad->id) {
                    case 1:
                    $prioridadClass = 'prioridadBaja';
                    break;
                    case 2:
                    $prioridadClass = 'prioridadMedia';
                    break;
                    case 3:
                    $prioridadClass = 'prioridadAlta';
                    break;
                    default:
                    $prioridadClass = 'prioridadBaja';
                    break;
                    }

                    $estadoClass = '';

                    switch ($orden->estado->id) {
                    case 1:
                    $estadoClass = 'estadoIniciada';
                    break;
                    case 2:
                    $estadoClass = 'estadoPendiente';
                    break;
                    case 3:
                    $estadoClass = 'estadoFinalizada';
                    break;
                    default:
                    $estadoClass = 'estadoPendiente';
                    break;
                    }
                    @endphp
                    <span class="infoEx {{ $prioridadClass }}" data-toggle="tooltip" data-placement="top"
                        title="Prioridad de la orden">{{ $orden->prioridad->descripcion_prioridad_ot }}</span>
                    <span class="infoEx {{ $estadoClass }}" data-toggle="tooltip" data-placement="top"
                        title="Estado de la orden">{{ $orden->estado->descripcion_estado_ot }}</span>
                    <span class="infoEx" data-toggle="tooltip" data-placement="top"
                        title="Tipo de orden">{{ $orden->tipo->descripcion_tipo_ot }}</span>
                    <span class="infoEx" data-toggle="tooltip" data-placement="top"
                        title="Tipo de visita de la orden">{{ $orden->tipoVisita->descripcion_tipo_visita }}</span>
                </div>
                <h5 class="card-title"><strong>Número Ot #{{ $orden->numero_ot }}</strong></h5>
                <p class="card-text"><strong>Precio Calculado</strong></p>
                @if(isset($orden->horas_ot))
                    <input type="text" name="" id="" class="form-control inputsText"
                        value="${{ number_format($orden->horas_ot * $orden->tecnicoEncargado->precio_hora_tecnico, 0, ',', '.') }}" disabled>
                @else
                    <input type="text" name="" id="" class="form-control inputsText" value="No asignado" disabled>
                @endif
                
                <p class="card-text"><strong>Ejecutivo de ventas</strong></p>
                @if(isset($orden->cod_ejecutivo))
                    <input type="text" name="" id="" class="form-control inputsText"
                        value="{{ html_entity_decode($orden->ejecutivo->nombre_usuario) }}" disabled>
                @else
                    <input type="text" name="" id="" class="form-control inputsText" value="No disponible" disabled>
                @endif
                
                <p class="card-text"><strong>Nombre del Cliente</strong></p>
                @if (isset($orden->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente))
                <input type="text" name="" id="" class="form-control inputsText"
                    value="{{ html_entity_decode($orden->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente) }}" disabled>
                @else
                <input type="text" name="" id="" class="form-control inputsText" value="No disponible" disabled>
                @endif

                <p class="card-text"><strong>Sucursal</strong></p>
                @if (isset($orden->contactoOt[0]->contacto->sucursal))
                <input type="text" name="" id="" class="form-control inputsText"
                    value="{{ html_entity_decode($orden->contactoOt[0]->contacto->sucursal->nombre_sucursal) }} - {{ html_entity_decode($orden->contactoOt[0]->contacto->sucursal->direccion_sucursal) }}" disabled>
                @else
                <input type="text" name="" id="" class="form-control inputsText" value="No disponible" disabled>
                @endif

                <p class="card-text"><strong>Contacto(s):</strong></p>
                <ul>
                    @forelse ($orden->contactoOt as $contacto)
                    @if (isset($contacto->contacto->nombre_contacto))
                    <li>{{ html_entity_decode($contacto->contacto->nombre_contacto) }}</li>
                    @else
                    <li>No disponible</li>
                    @endif
                    @empty
                    <li>No disponible</li>
                    @endforelse
                </ul>


                {{-- <p class="card-text"><strong>Tipo:</strong> {{ $orden->tipo->descripcion_tipo_ot }}</p> --}}
                {{-- <p class="card-text"><strong>Estado:</strong> {{ $orden->estado->descripcion_estado_ot }}</p> --}}
                <p class="card-text"><strong>Encargado</strong></p>
                <input type="text" name="" id="" class="form-control inputsText"
                    value="{{ html_entity_decode($orden->tecnicoEncargado->nombre_tecnico) }}" disabled>
                <!-- Mostrar técnicos participantes -->
                @if ($orden->EquipoTecnico)
                <label for="participantes"><strong>Tecnico(s):</strong></label>
                <ul>
                    @foreach ($orden->EquipoTecnico as $EquipoTecnico)
                    <li>{{ html_entity_decode($EquipoTecnico->tecnico->nombre_tecnico) }}</li>
                    @endforeach
                </ul>
                @else
                <p>No hay participantes asociados a esta orden.</p>
                @endif


                <p class="card-text"><strong>Servicio</strong> </p>
                <input type="text" name="" id="" class="form-control inputsText"
                    value="{{ html_entity_decode($orden->servicio->nombre_servicio) }}" disabled>
                {{-- <p class="card-text"><strong>Prioridad:</strong> {{ $orden->prioridad->descripcion_prioridad_ot }} --}}
                {{-- </p> --}}
                <p class="card-text"><strong>Fecha de creación de orden de trabajo</strong> </p>

                <input type="text" name="" id="" class="form-control inputsText"
                    value="{{ date('d-m-Y', strtotime($orden->created_at)) }}" disabled>

                @if ($orden->fecha_inicio_planificada_ot)
                <p class="card-text"><strong>Fecha de inicio de orden de trabajo</strong></p>
                <input type="text" name="" id="" class="form-control inputsText"
                    value="{{ date('d-m-Y', strtotime($orden->fecha_inicio_planificada_ot)) }}" disabled>
                @endif
                @if ($orden->fecha_fin_planificada_ot)
                <p class="card-text"><strong>Fecha estimada de finalización</strong></p>
                <input type="text" name="" id="" class="form-control inputsText"
                    value="{{ date('d-m-Y', strtotime($orden->fecha_fin_planificada_ot)) }}" disabled>
                @endif
                <p class="card-text"><strong>Cotización</strong></p>
                <input type="text" name="" id="" class="form-control inputsText"
                    value=@if ($orden->cotizacion) "{{ $orden->cotizacion }}"
                @else
                "No tiene cotización" @endif
                disabled>

                <p class="card-text"><strong>Horas</strong></p>
                <input type="text" name="" id="" class="form-control inputsText"
                    value="{{ $orden->horas_ot }}" disabled>

                <p class="card-text"><strong>Descripción</strong></p>
                <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="5" disabled>{{ html_entity_decode($orden->descripcion_ot) }}</textarea>
                {{-- <p class="card-text"><strong>Tipo de Visitas:</strong>
                        {{ $orden->tipoVisita->descripcion_tipo_visita }}</p> --}}
                <p class="card-text"><strong>Comentario:</strong>
                    @if ($orden->comentario_ot == null)
                    <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="5" disabled>Sin comentarios.</textarea>
                    @else
                    <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="5" disabled>{{ html_entity_decode($orden->comentario_ot) }}</textarea>
                    @endif
                </p>

                @if ($orden->servicio->cod_tipo_servicio == 1)
                <div class="accordion my-3" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button"
                                    data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">
                                    Tareas
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordionExample">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Tarea</th>
                                            <th scope="col">Tiempo duración (Minutos)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orden->TareasOt as $tarea)
                                        <tr>
                                            <td>{{ html_entity_decode($tarea->tarea->nombre_tarea) }}</td>
                                            <td>
                                                @php
                                                       
                                                    // Obtener el tiempo de la tarea para el servicio específico
                                                    $servicioTarea = $tarea->tarea->servicios()->where('cod_servicio', $orden->cod_servicio)->first();
                                                    $tiempoTarea = $servicioTarea ? $servicioTarea->pivot->tiempo : 'No disponible';
                                                @endphp
                                                    
                                                {{ $tiempoTarea }}
                                                {{-- $tarea->tarea->tiempo_tarea --}}
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                @else
                @if (count($orden->DispositivoOT) != 0)
                    @foreach ($orden->DispositivoOT as $dispositivo)
                    <div class="dispositivos">
                        <p class="card-text"><strong>Dispositivo</strong></p>
                        <p class="card-text"><strong>Número de serie:</strong>
                            {{ $dispositivo->dispositivo->numero_serie_dispositivo }}
                        </p>
                        <p class="card-text"><strong>Modelo:</strong>
                            {{ $dispositivo->dispositivo->modelo->nombre_modelo }}
                        </p>
                        <p class="card-text"><strong>Marca:</strong>
                            {{ $dispositivo->dispositivo->modelo->marca->nombre_marca }}
                        </p>
                        <div class="accordion my-3" id="accordionExample{{ $loop->iteration }}">
                            <div class="card">
                                <div class="card-header" id="heading{{ $loop->iteration }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button"
                                            data-toggle="collapse"
                                            data-target="#collapse{{ $loop->iteration }}"
                                            aria-expanded="false"
                                            aria-controls="collapse{{ $loop->iteration }}">
                                            Tareas
                                        </button>
                                    </h2>
                                </div>
    
                                <div id="collapse{{ $loop->iteration }}" class="collapse"
                                    aria-labelledby="heading{{ $loop->iteration }}"
                                    data-parent="#accordionExample{{ $loop->iteration }}">
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Tarea</th>
                                                    <th scope="col">Tiempo duración (Minutos)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dispositivo->tareaDispositivo as $tarea)
                                                <tr>
                                                    <td>{{ html_entity_decode($tarea->tarea->nombre_tarea) }}
                                                    </td>
                                                    <td>
                                                        @php
                                                           
                                                            // Obtener el tiempo de la tarea para el servicio específico
                                                            $servicioTarea = $tarea->tarea->servicios()->where('cod_servicio', $orden->cod_servicio)->first();
                                                            $tiempoTarea = $servicioTarea ? $servicioTarea->pivot->tiempo : 'No disponible';
                                                        @endphp
                                                        
                                                        {{ $tiempoTarea }}
                                                        {{-- $tarea->tarea->tiempo_tarea --}}
                                                    </td>
                                                </tr>
                                                @endforeach
    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
    
                        </div>
                        
                        
                        {{-- Repuestos Asociados --}}
                        @if ($dispositivo->repuestosDispositivo->isNotEmpty())
                            <div class="accordion my-3" id="accordionExampleR{{ $loop->iteration }}">
                                <div class="card">
                                    <div class="card-header" id="headingR{{ $loop->iteration }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button"
                                                data-toggle="collapse"
                                                data-target="#collapseR{{ $loop->iteration }}"
                                                aria-expanded="false"
                                                aria-controls="collapseR{{ $loop->iteration }}">
                                                Repuestos Asignados
                                            </button>
                                        </h2>
                                    </div>
                        
                                    <div id="collapseR{{ $loop->iteration }}" class="collapse"
                                        aria-labelledby="headingR{{ $loop->iteration }}"
                                        data-parent="#accordionExampleR{{ $loop->iteration }}">
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Nombre del Repuesto</th>
                                                        <th scope="col">Observación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dispositivo->repuestosDispositivo as $repuesto)
                                                        <tr>
                                                            <td>{{ $repuesto->repuesto->nombre_repuesto ?? 'Sin nombre' }}</td>
                                                            <td>{{ $repuesto->observacion_repuesto ?? 'Sin observación' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="card-text"><strong>No existen repuestos asignados a este dispositivo</strong></p>
                        @endif
                        
                        @if ($dispositivo->detalles)
                        <div class="accordion my-3" id="accordionExampleD{{ $loop->iteration }}">
                            <div class="card">
                                <div class="card-header" id="headingD{{ $loop->iteration }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button"
                                            data-toggle="collapse"
                                            data-target="#collapseD{{ $loop->iteration }}"
                                            aria-expanded="false"
                                            aria-controls="collapseD{{ $loop->iteration }}">
                                            Detalles
                                        </button>
                                    </h2>
                                </div>
    
                                <div id="collapseD{{ $loop->iteration }}" class="collapse"
                                    aria-labelledby="headingD{{ $loop->iteration }}"
                                    data-parent="#accordionExampleD{{ $loop->iteration }}">
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Detalle</th>
                                                    <th scope="col">Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Rayones</td>
                                                    <td>{{ $dispositivo->detalles->rayones_det }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Rupturas</td>
                                                    <td>{{ $dispositivo->detalles->rupturas_det }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tornillos</td>
                                                    <td>{{ $dispositivo->detalles->tornillos_det }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Gomas</td>
                                                    <td>{{ $dispositivo->detalles->gomas_det }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Estado del equipo</td>
                                                    <td>{{ $dispositivo->detalles->estado_dispositivo_det }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Observaciones adicionales</td>
                                                    <td>{{ $dispositivo->detalles->observaciones_det }}
                                                    </td>
                                                </tr>
    
    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
    
                        </div>
                        @else
                        <p class="card-text"><strong>No existe información de detalles en este
                                dispositivo</strong>
                        </p>
                        @endif
    
                        @if ($dispositivo->accesorios)
                        <div class="accordion my-3" id="accordionExampleA{{ $loop->iteration }}">
                            <div class="card">
                                <div class="card-header" id="headingA{{ $loop->iteration }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button"
                                            data-toggle="collapse"
                                            data-target="#collapseA{{ $loop->iteration }}"
                                            aria-expanded="false"
                                            aria-controls="collapseA{{ $loop->iteration }}">
                                            Accesorios
                                        </button>
                                    </h2>
                                </div>
    
                                <div id="collapseA{{ $loop->iteration }}" class="collapse"
                                    aria-labelledby="headingA{{ $loop->iteration }}"
                                    data-parent="#accordionExampleA{{ $loop->iteration }}">
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Accesorios</th>
                                                    <th scope="col">Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Cargador</td>
                                                    <td>{{ $dispositivo->accesorios->cargador_acc }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Cable de poder</td>
                                                    <td>{{ $dispositivo->accesorios->cable_acc }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Adaptador de poder</td>
                                                    <td>{{ $dispositivo->accesorios->adaptador_acc }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Batería</td>
                                                    <td>{{ $dispositivo->accesorios->bateria_acc }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Pantalla en mal estado</td>
                                                    <td>{{ $dispositivo->accesorios->pantalla_acc }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Teclado en mal estado</td>
                                                    <td>{{ $dispositivo->accesorios->teclado_acc }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Drum</td>
                                                    <td>{{ $dispositivo->accesorios->drum_acc }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Toner</td>
                                                    <td>{{ $dispositivo->accesorios->toner_acc }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
    
                        </div>
                        @else
                        <p class="card-text"><strong>No existe información de accesorios en este
                                dispositivo</strong>
                        </p>
                        @endif
                    </div>
                    @endforeach
                @endif

                @endif
                {{-- 
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div></div>
                    <a href="{{ route('ordenes.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>
                --}}
                {{-- <a href="{{ route('ordenes.index') }}" class="btn btn-primary">Volver</a> --}}
            </div>
        </div>
    </div>
</main>
<script src="{{ asset('assets/js/ordenes/detalleOrden.js') }}"></script>
@endsection
