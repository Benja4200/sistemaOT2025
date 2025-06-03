@extends('layouts.master')

@section('content')
<main id="main-content" class="flex-grow bg-gray-100 py-6" style="min-height: 100vh;">
<div class="container-fluid bg-light p-3">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('misOrdenes') }}"
           class="btn"
           style="background-color: #cc6633; border-color: #cc6633; color: #ffffff;">
            <i class="fa-solid fa-circle-left"></i>
            <span>Regresar</span>
        </a>
    </div>

    <h4 class="mb-3">Finalizar OT: {{ $orden->numero_ot }}</h4>

    <div class="bg-white rounded shadow-sm p-3">
        <div class="mb-3">
            <strong>Descripción de la Orden</strong>
            <p class="text-muted border-bottom">{{ $orden->descripcion_ot }}</p>
        </div>

        <div class="mb-3">
            <strong>Estado</strong>
            <p class="text-muted border-bottom">{{ $orden->estado->descripcion_estado_ot }}</p>
        </div>

        <div class="mb-3">
            <strong>Prioridad</strong>
            <p class="text-muted border-bottom">{{ $orden->prioridad->descripcion_prioridad_ot }}</p>
        </div>

        <form action="{{ route('ordenes.finalizar', $orden->numero_ot) }}" method="POST">
            @csrf

            {{-- Mensaje de error si faltan tareas --}}
            @if($errors->has('tareas'))
                <div class="alert alert-danger">
                    {{ $errors->first('tareas') }}
                </div>
            @endif

            {{-- Iteramos por cada dispositivo --}}
            <h4 class="fw-bold">
                Lista de Tareas
            </h4>
            <hr>
            @foreach($dispositivos as $disp)
                <div class="mb-4 mt-2">
                    <h5 class="fw-bold">
                        Dispositivo: {{ $disp['numero_serie'] }} —
                        {{ $disp['modelo'] }}
                    </h5>
                    <table class="table table-bordered table-striped table-responsive">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 10%">Seleccionar</th>
                                <th>Nombre de la Tarea</th>
                                <th style="width: 15%">Tiempo</th>
                                <th>Observación</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disp['tareas'] as $tarea)
                            <tr>
                                <td class="text-center align-middle">
                                    <input type="checkbox"
                                           name="tareas[]"
                                           value="{{ $tarea['id'] }}"
                                           id="tarea-{{ $disp['numero_serie'] }}-{{ $tarea['id'] }}">
                                </td>
                                <td class="align-middle">{{ $tarea['nombre'] }}</td>
                                <td class="align-middle">{{ $tarea['tiempo'] }} mins</td>
                                @if($tarea['requiere_obs'] == 'Si')
                                    <td class="align-middle">
                                        <input type="text"
                                               name="observacion_tarea[{{ $tarea['id'] }}]"
                                               class="form-control"
                                               placeholder="Ingrese observación"
                                               value="{{ old('observacion_tarea.' . $tarea['id']) }}">
                                        @error('observacion_tarea.' . $tarea['id'])
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                @else
                                <td>
                                    No requiere.
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
                            
            @if(!$orden->TareasOt->isEmpty())
                <div class="mb-4">

                    <table class="table table-bordered table-striped table-responsive">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 10%">Seleccionar</th>
                                <th>Nombre de la Tarea</th>
                                <th style="width: 15%">Tiempo</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orden->TareasOt as $tarea)
                            <tr>
                                <td class="text-center align-middle">
                                    <input type="checkbox"
                                           name="tareas[]"
                                           value="{{ $tarea->tarea->id }}"
                                           id="tarea-{{ $tarea->tarea->id }}">
                                </td>
                                <td class="align-middle">{{ html_entity_decode($tarea->tarea->nombre_tarea) }}</td>
                                <td class="align-middle">
                                    @php
                                        // Obtener el tiempo de la tarea para el servicio específico
                                        $servicioTarea = $tarea->tarea->servicios()->where('cod_servicio', $orden->cod_servicio)->first();
                                        $tiempoTarea = $servicioTarea ? $servicioTarea->pivot->tiempo : 'No disponible';
                                    @endphp
                                    {{ $tiempoTarea }}
                                </td>
                            
                                <!-- Mostrar el input de observación si requiere_obs es "Si" -->
                                @if($tarea->tarea->requiere_obs == "Si")
                                <td class="align-middle">
                                    <input type="text"
                                           name="observacion_tarea[{{ $tarea->tarea->id }}]"
                                           class="form-control"
                                           placeholder="Ingrese observación"
                                           value="{{ old('observacion_tarea.' . $tarea->tarea->id) }}"
                                           required>
                                    @error('observacion_tarea.' . $tarea->tarea->id)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                                @else
                                <td>
                                    No requiere.
                                </td>
                                @endif
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mb-4">
                <label for="comentario_avance" class="form-label">Descripción avance Final</label>
                <input type="text"
                       name="comentario_avance"
                       id="comentario_avance"
                       class="form-control"
                       value="{{ old('comentario_avance') }}"
                       required>
                @error('comentario_avance')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            

            <button type="submit"
                    id="finalizarBtn"
                    class="btn btn-danger"
                    disabled>
                <i class="fas fa-check-circle"></i> Finalizar OT
            </button>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Todos los checkboxes de tareas
    const checkboxes = document.querySelectorAll('input[name="tareas[]"]');
    const btn = document.getElementById('finalizarBtn');

    function toggleButton() {
        // Se activa solo si TODOS están marcados
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        btn.disabled = !allChecked;
    }

    checkboxes.forEach(cb => cb.addEventListener('change', toggleButton));
    // Chequeo inicial
    toggleButton();
});
</script>


@endsection