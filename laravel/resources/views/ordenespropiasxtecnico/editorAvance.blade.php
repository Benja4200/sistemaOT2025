@extends('layouts.master') {{-- Cambiado para usar el master de Bootstrap --}}

@section('content')
<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;"> {{-- Equivalente a py-6 de Tailwind, Bootstrap usa unidades de espaciado diferentes --}}
    <div class="container"> {{-- Similar a container de Tailwind, pero Bootstrap maneja los anchos de forma diferente --}}
        <div class="row">
            <div class="col-12"> {{-- Ocupa todo el ancho disponible --}}
                
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div> 
                        <h2 class="h2 font-weight-bold">Avances</h2>
                        <div class="ot-info-summary mt-2 text-muted small"> 
                            <p>
                                Estado: 
                                <span id="ot-status" class="font-weight-bold">
                                    {{ $orden->estado->descripcion_estado_ot ?? 'Cargando estado...' }}
                                </span>
                            </p>
                            <p>
                                Número OT: 
                                <span id="ot-status" class="font-weight-bold">
                                    {{ $orden->numero_ot ?? 'Cargando número...' }}
                                </span>
                            </p>
                            @if(isset($orden->estado) && $orden->estado->descripcion_estado_ot === 'Finalizada' && isset($orden->fecha_finalizacionexd_xot))
                                <p>
                                    Hora Finalización: 
                                    <span id="finalization-time" class="font-weight-bold">
                                        {{ $orden->fecha_finalizacionexd_xot instanceof \Carbon\Carbon ? $orden->fecha_finalizacionexd_xot->format('Y-m-d H:i:s') : ($orden->fecha_finalizacionexd_xot ?? 'Fecha no disponible') }}
                                    </span>
                                </p>
                                <p id="delay-time-display" class="d-none text-danger font-weight-bold mt-1">Tiempo de Atraso Final: <span id="delay-time-final">--:--:--</span></p>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        
                        {{--
                        <a href="{{ route('misOrdenes') }}" class="btn btn-danger btn-sm rounded ml-4">
                            <i class="fa-solid fa-circle-left"></i> Regresar
                        </a>
                        --}}
                    </div>
                </div>
    
    
    <!-- Primera columna de botones -->
    @if(isset($orden) && $orden->fecha_inicio_xot) 
    <div class="d-flex justify-content-end"> <!-- Contenedor que alinea a la derecha -->
        <div id="stopwatch-wrapper" class="p-3 border rounded shadow">
            <div id="stopwatch-container">
                <div class="d-flex align-items-center"> 
                    <i class="fa-solid fa-clock mr-2 text-secondary"></i> 
                    <span id="stopwatch" class="h4 text-monospace text-secondary">--:--:--</span> 
                    @if(isset($orden->estado) && $orden->estado->descripcion_estado_ot === 'Pendiente')
                        <span class="ml-2 small text-muted">(PAUSADO)</span>
                    @endif
                </div>
                <div id="estimated-completion-container" class="small text-muted mt-1"> 
                    Hora Estimada Fin: <span id="estimated-completion-time">--:--:--</span> 
                </div>
            </div>
        </div>
    </div>
@endif

    <div class="row">
  <!-- Botón 1: Agregar Avance -->
  <div class="col-6 col-md-4 d-flex justify-content-center">
    @if($orden->estado->id != 3)
      <button id="btnAgregarAvance" class="btn btn-danger btn-sm rounded m-1">
        Agregar avance
      </button>
    @else
      <button id="btnAgregarAvance" class="btn btn-secondary btn-sm rounded m-1" disabled>
        Agregar avance
      </button>
    @endif
  </div>

  <!-- Botón 2: Iniciar OT -->
  <div class="col-6 col-md-4 d-flex justify-content-center">
    @if($orden->estado->descripcion_estado_ot === 'Creada')
      <button id="btnIniciarOtForm" class="btn btn-success btn-sm rounded m-1">
        Iniciar OT
      </button>
    @else
      <button id="btnIniciarOtForm" class="btn btn-secondary btn-sm rounded m-1" disabled>
        Iniciar OT
      </button>
    @endif
  </div>

  <!-- Botón 3: Poner Pendiente -->
  <div class="col-6 col-md-4 d-flex justify-content-center">
    @if($orden->estado->id == 1)
      <button id="btnPendienteOtForm" class="btn btn-warning btn-sm rounded text-dark m-1">
        Poner Pendiente
      </button>
    @else
      <button id="btnPendienteOtForm" class="btn btn-secondary btn-sm rounded m-1" disabled>
        Poner Pendiente
      </button>
    @endif
  </div>

  <!-- Botón 4: Reanudar OT -->
  <div class="col-6 col-md-4 d-flex justify-content-center">
    @if($orden->estado->descripcion_estado_ot === 'Pendiente' || $orden->estado->descripcion_estado_ot === 'Finalizada')
      <form action="{{ route('reanudar_ot', $orden->numero_ot) }}" method="POST" class="w-100 text-center">
        @csrf 
        <button type="submit" class="btn btn-primary btn-sm rounded m-1">
          Reanudar OT
        </button>
      </form>
    @else
      <form action="{{ route('reanudar_ot', $orden->numero_ot) }}" method="POST" class="w-100 text-center">
        @csrf 
        <button type="submit" class="btn btn-secondary btn-sm rounded m-1" disabled>
          Reanudar OT
        </button>
      </form>
    @endif
  </div>

  <!-- Botón 5: Finalizar OT -->
  <div class="col-6 col-md-4 d-flex justify-content-center">
    @if($orden->estado->id == 1)
      <button id="btnFinalizarOtForm" class="btn btn-danger btn-sm rounded m-1" onclick="window.location='{{ route('ordenes.mostrarFinalizarOt', $orden->numero_ot) }}'">
        Finalizar OT
      </button>
    @else
      <button id="btnFinalizarOtForm" class="btn btn-secondary btn-sm rounded m-1" disabled onclick="window.location='{{ route('ordenes.mostrarFinalizarOt', $orden->numero_ot) }}'">
        Finalizar OT
      </button>
    @endif
  </div>

  <!-- Botón 6: Repuestos Asignados -->
  <div class="col-6 col-md-4 d-flex justify-content-center">
    <a href="{{ route('ordenes.repuestosUtilizados', $orden->numero_ot) }}">
      <button class="btn btn-secondary btn-sm rounded m-1">
        Repuestos Asignados
      </button>
    </a>
  </div>
</div>




                @if($orden->estado->descripcion_estado_ot !== 'Finalizada')
                <div class="modal fade" id="modalAgregarAvance" tabindex="-1" role="dialog" aria-labelledby="modalAgregarAvanceLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAgregarAvanceLabel">Agregar Avance</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{-- Session messages y errors se mostrarán vía SweetAlert como en tu script original --}}
                                <form action="{{ route('ordenes.avances.store', $orden->numero_ot) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="comentario_avance_modal_agregar">Descripcion avance</label>
                                        <input type="text" id="comentario_avance_modal_agregar" name="comentario_avance" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tiempo_avance_modal_agregar">Tiempo en mins</label>
                                        <input type="number" id="tiempo_avance_modal_agregar" name="tiempo_avance" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="imagen_avance_modal_agregar">Imagen del avance</label>
                                        <input type="file" id="imagen_avance_modal_agregar" name="imagen_avance" accept="image/*" class="form-control-file">
                                    </div>
                                    <button type="submit" class="btn btn-primary"> {{-- btn-pink-600 a btn-primary o color Bootstrap --}}
                                        <i class="fas fa-save"></i> Guardar Avance
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="modal fade" id="modalIniciarOt" tabindex="-1" role="dialog" aria-labelledby="modalIniciarOtLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalIniciarOtLabel">Iniciar OT</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('ordenes.iniciaravancesot', $orden->numero_ot) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="comentario_avance_modal_iniciar">Descripcion avance Inicio</label>
                                        <input type="text" id="comentario_avance_modal_iniciar" name="comentario_avance" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check-circle"></i> Iniciar OT
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="modalPendienteOt" tabindex="-1" role="dialog" aria-labelledby="modalPendienteOtLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalPendienteOtLabel">Marcar OT como Pendiente</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('ordenesxt.pendientex', $orden->numero_ot) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="comentario_avance_modal_pendiente">Comentario</label>
                                        <input type="text" id="comentario_avance_modal_pendiente" name="comentario_avance" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning text-dark"> {{-- text-dark para contraste en botón amarillo --}}
                                        <i class="fas fa-exclamation-circle"></i> Marcar como Pendiente
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="card mt-4">
                    <div class="card-header bg-light"> {{-- O puedes usar bg-secondary text-white si prefieres --}}
                        <h5 class="mb-0">Lista de Avances</h5>
                    </div>
                    <div class="card-body">
                        @if($orden->avances->isEmpty())
                            <p class="text-muted">No hay avances registrados para esta OT.</p>
                        @else
                            <ul class="list-unstyled">
                                @foreach($orden->avances as $avance)
                                    <li class="media mb-4 p-3 bg-light border rounded shadow-sm">
                                        <div class="media-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mt-0 mb-1 font-weight-bold text-primary">{{ $avance->fecha_avance }}</h6>
                                                    <p class="mb-1"><strong>Descripcion avance: </strong> <br> {!! nl2br($avance->comentario_avance) !!}</p>

                                                    <span class="badge badge-info">{{ $avance->tiempo_avance }} mins</span>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <button onclick="abrirModalEditar({{ $avance->id }})" class="btn btn-warning">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </button>
                                                    <button onclick="abrirModalEliminar({{ $avance->id }})" class="btn btn-danger">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        
                                            @if($avance->imagen)
                                                <img src="{{ asset($avance->imagen) }}" alt="Imagen del avance" class="img-fluid mt-2 rounded" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                            @else
                                                <p class="small text-muted mt-1">No hay imagen disponible</p>
                                            @endif
                                        </div>
                                        
                                        <div class="modal fade" id="modal-editar-{{ $avance->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel-{{ $avance->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditarLabel-{{ $avance->id }}">Editar Avance</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="form-editar-{{ $avance->id }}" onsubmit="guardarEdicion(event, {{ $avance->id }})" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT') {{-- Asegúrate que la ruta de actualización acepte PUT/PATCH --}}
                                                            
                                                            <div class="form-group">
                                                                <label for="comentario_avance_editar_{{ $avance->id }}">Descripción del avance</label>
                                                                <textarea name="comentario_avance" id="comentario_avance_editar_{{ $avance->id }}" class="form-control" required>{{ $avance->comentario_avance }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="fecha_avance_editar_{{ $avance->id }}">Fecha</label>
                                                                <input type="datetime-local" name="fecha_avance" id="fecha_avance_editar_{{ $avance->id }}" value="{{ date('Y-m-d\TH:i', strtotime($avance->fecha_avance)) }}" class="form-control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tiempo_avance_editar_{{ $avance->id }}">Tiempo (mins)</label>
                                                                <input type="number" name="tiempo_avance" id="tiempo_avance_editar_{{ $avance->id }}" value="{{ $avance->tiempo_avance }}" class="form-control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="imagen_avance_editar_{{ $avance->id }}">Nueva imagen (opcional)</label>
                                                                <input type="file" name="imagen_avance" id="imagen_avance_editar_{{ $avance->id }}" class="form-control-file">
                                                            </div>
                                                            @if($avance->imagen)
                                                            <div class="form-group">
                                                                <label>Imagen actual</label><br>
                                                                <img src="{{ asset($avance->imagen) }}" alt="Imagen actual" class="img-thumbnail" style="max-width: 100px;">
                                                            </div>
                                                            @endif
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal fade" id="modal-eliminar-{{ $avance->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel-{{ $avance->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEliminarLabel-{{ $avance->id }}">Eliminar Avance</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>¿Estás seguro de que deseas eliminar este avance?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        {{-- Cambiado para que el form esté dentro del modal footer para mejor estructura --}}
                                                        <form id="form-eliminar-{{ $avance->id }}" onsubmit="confirmarEliminacion(event, {{ $avance->id }})">
                                                            @csrf 
                                                            @method('DELETE') {{-- Asegúrate que la ruta de eliminación acepte DELETE --}}
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                @if($orden->avances->isNotEmpty())
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                             <h5 class="mb-0">Último Avance para la Finalización</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Descripcion Avance:</strong> <br> {!! nl2br($orden->avances->last()->comentario_avance) !!}</p>
                            <p><strong>Fecha:</strong> {{ $orden->avances->last()->fecha_avance }}</p>
                            <p><strong>Tiempo:</strong> {{ $orden->avances->last()->tiempo_avance }} mins</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Manejadores de SweetAlert para sesiones y errores (ya deberían estar en tu master o app.js si son globales)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Aceptar'
            });
        @endif
        
        @if ($errors->any())
            let errorText = '';
            @foreach ($errors->all() as $error)
                errorText += "{{ $error }}<br>";
            @endforeach
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                html: errorText,
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if(session('fechaCalculada'))
            Swal.fire({
                icon: 'success',
                title: 'OT Iniciada',
                text: "La OT ha sido iniciada exitosamente. Fecha calculada: {{ session('fechaCalculada') }}. Tiempo total de tareas: {{ session('tiempoTotalTareas') }} minutos.",
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if(session('error')) // Error general de sesión
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Aceptar'
            });
        @endif


        // Abrir modales de Bootstrap
        $('#btnAgregarAvance').on('click', function() {
            @if($orden->estado->descripcion_estado_ot !== 'Finalizada')
                $('#modalAgregarAvance').modal('show');
            @endif
        });

        $('#btnIniciarOtForm').on('click', function() {
            $('#modalIniciarOt').modal('show');
        });

        $('#btnPendienteOtForm').on('click', function() {
            $('#modalPendienteOt').modal('show');
        });
        // El botón Finalizar OT ya tiene un onclick para redirigir
    });

    function abrirModalEditar(avanceId) {
        $(`#modal-editar-${avanceId}`).modal('show');
    }
    
    function abrirModalEliminar(avanceId) {
        $(`#modal-eliminar-${avanceId}`).modal('show');
    }
    
    // Función para guardar la edición (AJAX)
    function guardarEdicion(event, avanceId) {
        event.preventDefault();
        
        const form = document.getElementById(`form-editar-${avanceId}`);
        const formData = new FormData(form);
        
        // Opcional: Mostrar un loader en el botón de submit del modal
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
        submitButton.disabled = true;

        fetch(`/avances/${avanceId}/actualizar`, { // Reemplaza con tu URL correcta para actualizar
            method: 'POST', // Laravel maneja PUT/PATCH a través de un campo _method en FormData
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json' // Es importante para que Laravel devuelva JSON si hay errores de validación
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                // Construir mensaje de error si hay errores de validación
                let errorMsg = data.message || 'Error al actualizar el avance.';
                if (data.errors) {
                    errorMsg += '<br><ul class="text-left">';
                    for (const key in data.errors) {
                        data.errors[key].forEach(err => {
                            errorMsg += `<li>${err}</li>`;
                        });
                    }
                    errorMsg += '</ul>';
                }
                const error = new Error(errorMsg);
                error.data = data; // adjuntar datos completos por si se necesitan
                throw error;
            }
            return data;
        })
        .then(data => {
            if (data.success || data.mensaje) { // Ajusta según la respuesta de tu backend
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.mensaje || 'Avance actualizado correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $(`#modal-editar-${avanceId}`).modal('hide'); // Cerrar modal de Bootstrap
                    location.reload(); // Recargar la página para ver cambios
                });
            } else {
                 Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: data.mensaje || 'Ocurrió un error inesperado.',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            console.error('Error en fetch:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de Comunicación',
                html: error.message || 'No se pudo conectar con el servidor.',
                confirmButtonText: 'Aceptar'
            });
        })
        .finally(() => {
            // Restaurar botón
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        });
    }
    
    // Función para confirmar eliminación (AJAX)
    function confirmarEliminacion(event, avanceId) {
        event.preventDefault(); // Prevenir el envío normal del formulario
        console.log('confirmarEliminacion function called for avanceId:', avanceId);
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Color rojo de Bootstrap para peligro
            cancelButtonColor: '#6c757d', // Color gris de Bootstrap para secundario
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(`form-eliminar-${avanceId}`);
                const formData = new FormData(form); // Aunque para DELETE no suele haber body, CSRF y method sí son útiles
                
                // Opcional: loader en algún sitio si la acción tarda
                
                fetch(`/avancesctx/${avanceId}/eliminarctx`, { // Reemplaza con tu URL correcta para eliminar
                    method: 'DELETE', // Laravel maneja esto directamente si la ruta lo espera
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // O '{{ csrf_token() }}' si está disponible
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    // body: formData // No es común para DELETE, pero si envías algo, inclúyelo.
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        const error = new Error(data.message || data.mensaje || 'Error al eliminar el avance.');
                        error.data = data;
                        throw error;
                    }
                    return data;
                })
                .then(data => {
                    if (data.success || data.mensaje) {
                        Swal.fire(
                            '¡Eliminado!',
                            data.mensaje || 'El avance ha sido eliminado.',
                            'success'
                        ).then(() => {
                             $(`#modal-eliminar-${avanceId}`).modal('hide');
                            location.reload(); // Recargar la página
                        });
                    } else {
                        Swal.fire('Error', data.mensaje || 'No se pudo eliminar el avance.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error en fetch (eliminar):', error);
                    Swal.fire('Error de Comunicación', error.message || 'No se pudo conectar con el servidor.', 'error');
                });
            }
        });
    }

    // Script del cronómetro (se mantiene la lógica original, solo asegúrate que los IDs de los elementos existan en el HTML)
    @if(isset($orden) && $orden->fecha_inicio_xot)
        console.log('--- Script cronómetro con pausa, estimado, estado Finalizada y ALERTA (Bootstrap version) ---'); 

        const currentOtStatus = "{{ $orden->estado->descripcion_estado_ot ?? '' }}";
        const otStartTimeString = "{{ $orden->fecha_inicio_xot }}";
        const otPauseTimeString = "{{ $orden->fecha_pausa_xot ?? '' }}";
        const cumulativePausedSeconds = {{ $orden->cumulative_paused_seconds ?? 0 }};
        const estimatedTaskMinutes = {{ $tiempoTotalTareasEstimado ?? 0 }};

        const finalizationTimeString = "{{ (isset($orden->estado) && $orden->estado->descripcion_estado_ot === 'Finalizada' && isset($orden->fecha_finalizacionexd_xot) && $orden->fecha_finalizacionexd_xot instanceof \Carbon\Carbon) ? $orden->fecha_finalizacionexd_xot->format('Y-m-d H:i:s') : ((isset($orden->estado) && $orden->estado->descripcion_estado_ot === 'Finalizada' && isset($orden->fecha_finalizacionexd_xot)) ? $orden->fecha_finalizacionexd_xot : '') }}";
        const finalizationDate = finalizationTimeString ? new Date(finalizationTimeString.replace(' ', 'T')) : null;
        
        const delaySecondsRaw = {{ $orden->delay_seconds_xot ?? 'null' }}; 

        console.log('Estado ACTUAL:', currentOtStatus);
        console.log('Fecha Inicio OT:', otStartTimeString);
        console.log('Fecha Última Pausa OT:', otPauseTimeString);
        console.log('Pausa Acumulada (segundos):', cumulativePausedSeconds);
        console.log('Tiempo Estimado (minutos):', estimatedTaskMinutes);
        console.log('Fecha Finalización OT:', finalizationTimeString);
        console.log('Delay Seconds Raw:', delaySecondsRaw);

        const stopwatchElement = document.getElementById('stopwatch');
        const estimatedCompletionTimeElement = document.getElementById('estimated-completion-time');
        const estimatedCompletionContainer = document.getElementById('estimated-completion-container');
        
        const delayTimeDisplayElement = document.getElementById('delay-time-display');
        const delayTimeFinalSpan = document.getElementById('delay-time-final');

        const otStartTime = new Date(otStartTimeString.replace(' ', 'T'));
        const otPauseTime = otPauseTimeString ? new Date(otPauseTimeString.replace(' ', 'T')) : null;
        const initialTotalSeconds = estimatedTaskMinutes * 60;

        let intervalId = null;
        let alertShownNegative = false;
        let alertShownFiveMinutes = false;

        function formatTime(totalSeconds) {
             const isNegative = totalSeconds < 0;
             const absTotalSeconds = Math.abs(totalSeconds);
             const hours = Math.floor(absTotalSeconds / 3600);
             const minutes = Math.floor((absTotalSeconds % 3600) / 60);
             const seconds = Math.floor(absTotalSeconds % 60);
             const paddedHours = String(hours).padStart(2, '0');
             const paddedMinutes = String(minutes).padStart(2, '0');
             const paddedSeconds = String(seconds).padStart(2, '0');
             return (isNegative ? '-' : '') + `${paddedHours}:${paddedMinutes}:${paddedSeconds}`;
        }

        function formatDateTime(date) {
            if (!(date instanceof Date) || isNaN(date)) return '--:--:--';
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        function updateStopwatchDynamic() {
            const currentTime = new Date();
            const totalElapsedTimeSinceStart = Math.floor((currentTime.getTime() - otStartTime.getTime()) / 1000);
            let currentPauseDuration = 0;
            if (currentOtStatus === 'Pendiente' && otPauseTime) {
                 currentPauseDuration = Math.floor((currentTime.getTime() - otPauseTime.getTime()) / 1000);
                 currentPauseDuration = Math.max(0, currentPauseDuration);
            }
            const totalPausedSeconds = cumulativePausedSeconds + currentPauseDuration;
            const totalActiveSeconds = totalElapsedTimeSinceStart - totalPausedSeconds;
            const remainingSeconds = initialTotalSeconds - totalActiveSeconds;

            if (stopwatchElement) {
                 stopwatchElement.textContent = formatTime(remainingSeconds);
                 if (currentOtStatus === 'Iniciada') {
                     if (remainingSeconds < 0) { stopwatchElement.classList.add('text-danger'); stopwatchElement.classList.remove('text-warning', 'text-secondary'); } // Bootstrap text-danger
                     else if (remainingSeconds <= 300) { stopwatchElement.classList.add('text-warning'); stopwatchElement.classList.remove('text-danger', 'text-secondary'); } // Bootstrap text-warning
                     else { stopwatchElement.classList.add('text-secondary'); stopwatchElement.classList.remove('text-danger', 'text-warning'); } 
                 } else { 
                      stopwatchElement.classList.add('text-secondary'); // Paused color
                      stopwatchElement.classList.remove('text-danger', 'text-warning');
                 }
            }

            if (estimatedCompletionTimeElement && estimatedCompletionContainer) {
                 estimatedCompletionContainer.style.display = 'block';
                 const estimatedCompletionTimeMs = currentTime.getTime() + (remainingSeconds * 1000);
                 const estimatedCompletionDate = new Date(estimatedCompletionTimeMs);
                 estimatedCompletionTimeElement.textContent = formatDateTime(estimatedCompletionDate);
                 if (remainingSeconds < 0) {
                     estimatedCompletionTimeElement.textContent += ' (¡Retrasada!)';
                     estimatedCompletionTimeElement.classList.add('text-danger'); // Bootstrap text-danger
                 } else {
                     estimatedCompletionTimeElement.classList.remove('text-danger');
                 }
            }

            if (currentOtStatus === 'Iniciada') {
                if (remainingSeconds < 0 && !alertShownNegative) {
                     Swal.fire({ icon: 'warning', title: '¡Tiempo Estimado Excedido!', text: 'La OT ha superado el tiempo estimado.', confirmButtonText: 'Aceptar' });
                     alertShownNegative = true;
                }
                if (remainingSeconds <= 300 && remainingSeconds >= 0 && !alertShownFiveMinutes) {
                     Swal.fire({ icon: 'info', title: '¡Atención!', text: 'Faltan 5 minutos o menos para el tiempo estimado.', confirmButtonText: 'Aceptar' });
                     alertShownFiveMinutes = true;
                }
            }
        }

        function startStopwatchInterval() {
             if (currentOtStatus === 'Iniciada' && intervalId === null) {
                 console.log('Iniciando intervalo del cronómetro...');
                 intervalId = setInterval(updateStopwatchDynamic, 1000);
             }
        }

        function stopStopwatchInterval() {
             if (intervalId !== null) {
                clearInterval(intervalId);
                intervalId = null;
                console.log('Intervalo del cronómetro detenido.');
             }
        }

        function initializeStopwatchDisplay() {
            if (currentOtStatus === 'Finalizada' && finalizationDate) {
                console.log('OT Finalizada. Mostrando tiempo final calculado.');
                const totalElapsedTimeAtFinalization = Math.floor((finalizationDate.getTime() - otStartTime.getTime()) / 1000);
                const totalActiveSecondsAtFinalization = totalElapsedTimeAtFinalization - cumulativePausedSeconds;
                const finalRemainingSeconds = initialTotalSeconds - totalActiveSecondsAtFinalization;

                if (stopwatchElement) {
                    stopwatchElement.textContent = formatTime(finalRemainingSeconds);
                    if (finalRemainingSeconds < 0) { stopwatchElement.classList.add('text-danger'); } else { stopwatchElement.classList.add('text-secondary');}
                    stopwatchElement.classList.remove('text-warning');

                    const pausadoLabel = stopwatchElement.parentElement.querySelector('.small.text-muted'); // Busca el span (PAUSADO)
                     if(pausadoLabel && pausadoLabel.textContent.includes('(PAUSADO)')) {
                         pausadoLabel.style.display = 'none';
                     }
                }
                if (estimatedCompletionContainer) {
                    estimatedCompletionContainer.style.display = 'none';
                }

                if (delayTimeDisplayElement && delayTimeFinalSpan && delaySecondsRaw !== null && delaySecondsRaw < 0) {
                    delayTimeFinalSpan.textContent = formatTime(Math.abs(delaySecondsRaw)); 
                    delayTimeDisplayElement.classList.remove('d-none'); // Bootstrap d-none
                    console.log('Mostrando tiempo de atraso final:', formatTime(Math.abs(delaySecondsRaw)));
                } else if (delayTimeDisplayElement) {
                    delayTimeDisplayElement.classList.add('d-none'); 
                }
                stopStopwatchInterval();

            } else if (currentOtStatus === 'Iniciada' || currentOtStatus === 'Pendiente') {
                console.log('OT Iniciada/Pendiente. Inicializando display dinámico.');
                if (estimatedCompletionContainer) estimatedCompletionContainer.style.display = 'block';
                updateStopwatchDynamic(); 
                if (currentOtStatus === 'Iniciada') {
                    startStopwatchInterval();
                } else { 
                    stopStopwatchInterval(); 
                }
                 const currentTime = new Date();
                 const totalElapsedTimeSinceStart = Math.floor((currentTime.getTime() - otStartTime.getTime()) / 1000);
                 let currentPauseDuration = 0;
                  if (currentOtStatus === 'Pendiente' && otPauseTime) {
                       currentPauseDuration = Math.floor((currentTime.getTime() - otPauseTime.getTime()) / 1000);
                       currentPauseDuration = Math.max(0, currentPauseDuration);
                  }
                 const totalPausedSeconds = cumulativePausedSeconds + currentPauseDuration;
                 const totalActiveSeconds = totalElapsedTimeSinceStart - totalPausedSeconds;
                 const initialRemainingSeconds = initialTotalSeconds - totalActiveSeconds;
                 alertShownNegative = initialRemainingSeconds < 0;
                 alertShownFiveMinutes = (initialRemainingSeconds <= 300 && initialRemainingSeconds >=0);

            } else { // Creada u otro estado
                 console.log('OT en estado diferente (ej. Creada). Cronómetro no visible/activo.');
                 const stopwatchContainerEl = document.getElementById('stopwatch-container');
                 if(stopwatchContainerEl) stopwatchContainerEl.style.display = 'none';
                 if(delayTimeDisplayElement) delayTimeDisplayElement.classList.add('d-none');
            }
        }

        document.addEventListener('DOMContentLoaded', initializeStopwatchDisplay);
        document.addEventListener('visibilitychange', () => {
             if (document.visibilityState === 'visible') {
                 console.log('Página visible. Reinicializando display/intervalo.');
                 initializeStopwatchDisplay(); 
             } else {
                 console.log('Página oculta. Deteniendo intervalo si estaba corriendo.');
                 stopStopwatchInterval();
             }
        });
        window.addEventListener('beforeunload', stopStopwatchInterval);

    @else
         console.log('Script cronómetro/estimado no inicializado. OT no iniciada originalmente.');
         const stopwatchContainerEl = document.getElementById('stopwatch-container');
         if(stopwatchContainerEl) stopwatchContainerEl.style.display = 'none';
         const delayTimeDisplayElement = document.getElementById('delay-time-display');
         if(delayTimeDisplayElement) delayTimeDisplayElement.classList.add('d-none');
    @endif
</script>
@endsection