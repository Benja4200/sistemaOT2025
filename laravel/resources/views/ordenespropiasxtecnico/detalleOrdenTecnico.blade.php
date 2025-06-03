@extends('layouts.master')

@section('content')
<div class="container-fluid bg-light p-3">
  <div class="d-flex justify-content-end mb-3">
    <a href="{{ route('misOrdenes') }}"
       class="btn"
       style="background-color: #cc6633; border-color: #cc6633; color: #ffffff;">
      <i class="fa-solid fa-circle-left"></i>
      <span>Regresar</span>
    </a>
  </div>

  <h4 class="mb-3">Detalles de Orden</h4>

  <div class="bg-white rounded shadow-sm p-3">
    @foreach ($ordenesTecnicox as $ordenesDeTecnicos)
      {{-- Encabezado OT --}}
      <div class="bg-secondary text-white rounded p-3 mb-3 w-auto">
        <p><strong>Orden de trabajo: </strong>{{ $ordenesDeTecnicos->numero_ot }}</p>
        <p><strong>Prioridad: </strong>{{ $ordenesDeTecnicos->prioridad->descripcion_prioridad_ot }}</p>
        <p><strong>Estado: </strong>{{ $ordenesDeTecnicos->estado->descripcion_estado_ot }}</p>
      </div>

      {{-- Datos de cliente y contacto --}}
      <div class="mb-2">
        <strong>Nombre del Cliente</strong>
        <p class="text-muted border-bottom">
            @if (isset($ordenesDeTecnicos->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente))
                {{ html_entity_decode($ordenesDeTecnicos->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente) }}
            @else
                No disponible
            @endif
        </p>
      </div>
      <div class="mb-2">
        <strong>Sucursal</strong>
        <p class="text-muted border-bottom">
            @if (isset($ordenesDeTecnicos->contactoOt[0]->contacto->sucursal))
                {{ html_entity_decode($ordenesDeTecnicos->contactoOt[0]->contacto->sucursal->nombre_sucursal) }} - {{ html_entity_decode($ordenesDeTecnicos->contactoOt[0]->contacto->sucursal->direccion_sucursal) }}
            @else
                No disponible
            @endif
        </p>
      </div>
      <div class="mb-2">
        <strong>Fono</strong>
        <p class="text-muted border-bottom">
            @if (isset($ordenesDeTecnicos->contactoOt[0]->contacto->sucursal->telefono_sucursal))
                {{ html_entity_decode($ordenesDeTecnicos->contactoOt[0]->contacto->sucursal->telefono_sucursal) }}
            @else
                No disponible
            @endif
        </p>
      </div>
                
      <div class="mb-2">
        <strong>Nombre Contacto(s)</strong>
           <ul>
                @forelse ($ordenesDeTecnicos->contactoOt as $contacto)
                @if (isset($contacto->contacto->nombre_contacto))
                <li>{{ html_entity_decode($contacto->contacto->nombre_contacto) }}</li>
                @else
                <li>No disponible</li>
                @endif
                @empty
                <li>No disponible</li>
                @endforelse
            </ul>
      </div>
      {{--
      <div class="mb-2">
        <strong>Fono Contacto</strong>
        <p class="text-muted border-bottom">{{ $ordenesDeTecnicos->contacto->telefono_contacto }}</p>
      </div>
        --}}

      {{-- Descripción y tipo/encargado --}}
      <div class="mb-3">
        <strong>Descripción</strong>
        <div class="border p-2 rounded">{{ $ordenesDeTecnicos->descripcion_ot }}</div>
      </div>
      <div class="mb-2">
        <strong>Tipo</strong>
        <p class="text-muted border-bottom">{{ $ordenesDeTecnicos->tipo->descripcion_tipo_ot }}</p>
      </div>
      <div class="mb-2">
        <strong>Encargado</strong>
        <p class="text-muted border-bottom">{{ $ordenesDeTecnicos->tecnicoEncargado->nombre_tecnico }}</p>
      </div>

      {{-- Equipo técnico --}}
      <div class="mb-2">
        <strong>Equipo Técnico asignado</strong>
        <ul class="list-unstyled">
          @foreach($obteniendo_equipo_tecnico as $nombre)
            <li>{{ $nombre }}</li>
          @endforeach
        </ul>
      </div>

      {{-- Servicio --}}
      <div class="mb-2">
        <strong>Servicio</strong>
        <p class="text-muted border-bottom">{{ $ordenesDeTecnicos->servicio->nombre_servicio }}</p>
      </div>

      {{-- *** Nueva Sección para Tareas Generales (No relacionadas a Dispositivos) *** --}}
      <div class="mb-4">
          <h5 class="fw-bold">Tareas Generales</h5>

          @forelse($ordenesDeTecnicos->TareasOt ?? [] as $tareaOt)
              @if($tareaOt->tarea) {{-- Asegurarse de que la relación 'tarea' existe --}}
                  <div class="card mb-2">
                      <ul class="list-group list-group-flush">
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                              {{ $tareaOt->tarea->nombre_tarea ?? 'Tarea sin nombre' }}
                              <span class="badge badge-secondary badge-pill">
                                @php
                                    // Obtener el tiempo de la tarea para el servicio específico
                                    $servicioTarea = $tareaOt->tarea->servicios()->where('cod_servicio', $ordenesDeTecnicos->cod_servicio)->first();
                                    $tiempoTarea = $servicioTarea ? $servicioTarea->pivot->tiempo : 'No disponible';
                                @endphp  
                                
                                @if($tiempoTarea == 'No disponible')
                                    {{ $tiempoTarea ?? '–' }}
                                @else
                                    {{ $tiempoTarea ?? '–' }} mins
                                @endif
                              </span>
                          </li>
                      </ul>
                  </div>
              @endif
          @empty
              <p class="text-muted">No hay tareas generales asignadas a esta orden.</p>
          @endforelse
      </div>
      {{-- *** Fin de la Nueva Sección *** --}}


      {{-- Dispositivos y sus tareas --}}
      <div class="mb-4">
        <h5 class="fw-bold">Dispositivos y Tareas</h5>

        @forelse($ordenesDeTecnicos->DispositivoOT ?? [] as $dispOt)
          @php
            // Cargamos tareas directamente
            $tareasDisp = \App\Models\TareaDispositivo::where('cod_dispositivo_ot', $dispOt->id)
                            ->with('tarea')
                            ->get();

            $disp   = $dispOt->dispositivo;
            $modelo = $disp && $disp->modelo
                      ? $disp->modelo->nombre_modelo
                      : 'Modelo no disponible';
            $serie  = $disp->numero_serie_dispositivo ?? 'N/D';
          @endphp

          <div class="card mb-2">
            <div class="card-header bg-light d-flex justify-content-between">
              <span><strong>Modelo:</strong> {{ $modelo }}</span>
              <span><strong>Serie:</strong> {{ $serie }}</span>
            </div>
            <ul class="list-group list-group-flush">
              @forelse($tareasDisp as $td)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{ $td->tarea->nombre_tarea ?? 'Tarea sin nombre' }}
                  <span class="badge badge-secondary badge-pill">
                    @php
                        // Obtener el tiempo de la tarea para el servicio específico
                        $servicioTarea = $td->tarea->servicios()->where('cod_servicio', $ordenesDeTecnicos->cod_servicio)->first();
                        $tiempoTarea = $servicioTarea ? $servicioTarea->pivot->tiempo : 'No disponible';
                    @endphp  
                      
                    @if($tiempoTarea == 'No disponible')
                                    {{ $tiempoTarea ?? '–' }}
                                @else
                                    {{ $tiempoTarea ?? '–' }} mins
                                @endif
                  </span>
                </li>
              @empty
                <li class="list-group-item text-muted">
                  No hay tareas asignadas a este dispositivo.
                </li>
              @endforelse
            </ul>
          </div>
          
          <!-- Tarjeta separada para los repuestos -->
        <div class="card mb-2">
            <div class="card-header bg-light">
                <strong>Repuestos asignados al dispositivo (Serie {{ $serie }})</strong>
            </div>

            <ul class="list-group list-group-flush">
                @forelse($dispOt->repuestosDispositivo as $repuesto)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <strong>{{ $repuesto->repuesto->nombre_repuesto ?? 'Repuesto sin nombre' }}</strong>
                            <br>
                            <small class="text-muted">Observación: {{ $repuesto->observacion_repuesto ?? 'Sin observación' }}</small>
                        </span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">
                        No hay repuestos asignados a este dispositivo.
                    </li>
                @endforelse
            </ul>
        </div>
        @empty
          <p class="text-muted">No hay dispositivos asignados a esta orden.</p>
        @endforelse
      </div>

      {{-- Actividades Extra --}}
      <div class="mb-4">
        <h5 class="fw-bold">Actividades Extra</h5>

        @if($actividadesExtras->isEmpty())
          <p class="text-muted">No hay actividades extra registradas.</p>
        @else
            <div class="table-responsive">
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Actividad</th>
                    <th>Horas</th>
                    <th>Fecha registro</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($actividadesExtras as $index => $act)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $act->nombre_actividad }}</td>
                      <td>{{ $act->horas_actividad }}</td>
                      <td>{{ $act->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
        @endif
      </div>

    @endforeach
  </div>
</div>
@endsection