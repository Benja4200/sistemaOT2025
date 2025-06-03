@extends('layouts.master')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Repuestos de la orden</h2>
                    <a href="{{ route('editor_avance',$orden->numero_ot) }}" class="btn btn-secondary" style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        Asignar repuestos a la orden
                    </div>
                    <div class="card-body">
                        
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3 mx-4">
                                <strong>¡Error!</strong> Corrige los siguientes problemas antes de continuar:
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <h4>Dispositivos de la orden</h4>
                        @forelse($orden->DispositivoOt as $dispositivoOT)
                            <div class="mx-4">
                                <h5>Número de serie: {{ $dispositivoOT->dispositivo->numero_serie_dispositivo }}</h5>
                                <h6>Modelo: {{ $dispositivoOT->dispositivo->modelo->nombre_modelo }}</h6>
                                
                                <div class="mt-2 table-responsive ">
                                    <table class="table  table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nombre repuesto</th>
                                                <th>Descripción repuesto</th>
                                                <th>Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            // Filtra los repuestos que no han sido asignados a este dispositivoOT.
                                            $repuestosDisponibles = $dispositivoOT->dispositivo->modelo->repuestos
                                                ->sortBy('nombre_repuesto')
                                                ->filter(function ($repuesto) use ($dispositivoOT) {
                                                    return !$dispositivoOT->repuestosDispositivo->pluck('cod_repuesto')->contains($repuesto->id);
                                                });
                                        @endphp
                                        
                                        @forelse($repuestosDisponibles as $repuesto)    
                                        
                                            <tr>
                                                <td>
                                                    {{ $repuesto->nombre_repuesto}}
                                                </td>
                                                <td>
                                                    {{ $repuesto->descripcion_repuesto}}
                                                </td>
                                                <td class="d-flex justify-content-center" style="gap: 4px;">
                                                   <div class="d-flex justify-content-center align-items-center">
                                                        <!-- Botón Editar -->
                                                        <a href="#" class="btn btn-primary" style="background-color: #cc6633; border-color: #cc6633;"
                                                           data-toggle="modal" data-target="#asignarModal-{{ $repuesto->id }}">
                                                            <i class="fas fa-edit"></i> Asignar
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <!-- Modal para el repuesto {{ $repuesto->id }} -->
                                            <div class="modal fade" id="asignarModal-{{ $repuesto->id }}" tabindex="-1" role="dialog" aria-labelledby="asignarModalLabel-{{ $repuesto->id }}" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <!-- Incluimos el formulario que se envía al controlador -->
                                                
                                                <form action="{{ route('ordenes.asignarRepuestoOrden')  }}" method="POST">
                                                  @csrf
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="asignarModalLabel-{{ $repuesto->id }}">Asignar Repuesto: {{ $repuesto->nombre_repuesto }}</h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h6>Se asignara este repuesto a la orden {{ $orden->numero_ot}}</h6>
                                                      <!-- Input hidden con el id del repuesto -->
                                                      <input type="hidden" name="repuesto_id" value="{{ $repuesto->id }}">
                                                      <input type="hidden" name="dispositivoOT_id" value="{{ $dispositivoOT->id }}">
                                                      
                                                      <!-- Input de texto para, por ejemplo, indicar un detalle o comentario -->
                                                      <div class="form-group">
                                                        <label for="detalleAsignacion-{{ $repuesto->id }}">Observación</label>
                                                        <input type="text" class="form-control" id="detalleAsignacion-{{ $repuesto->id }}" name="detalle_asignacion" placeholder="Ingresa la observación" required>
                                                      </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                      <!-- Botón para enviar el formulario -->
                                                      <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                    </div>
                                                  </div>
                                                </form>
                                              </div>
                                            </div>

                                        @empty
                                            <tr>
                                                <td colspan="3"> No existen repuestos asignados a este modelo en la base de datos o ya fueron asignados.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <div class="mx-4">
                                <h6>Orden sin dispositivos asignados.</h6>
                            </div>
                        @endforelse
                            
                            <div class="card mt-3 mx-4">
                                <div class="card-header">
                                    Repuestos asignados a la orden
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        
                                    
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>N° serie del dispositivo</th>
                                                    <th>Nombre del modelo</th>
                                                    <th>Nombre Repuesto</th>
                                                    <th>Observación</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($orden->DispositivoOT as $dispositivoOT)
                                                    @foreach($dispositivoOT->repuestosDispositivo as $repuestoDispositivo)
                                                        <tr>
                                                            <!-- Asumiendo que el nombre del dispositivo es su número de serie -->
                                                            <td>{{ $dispositivoOT->dispositivo->numero_serie_dispositivo }}</td>
                                                            <!-- Asumiendo que el nombre del dispositivo es su número de serie -->
                                                            <td>{{ $dispositivoOT->dispositivo->modelo->nombre_modelo }}</td>
                                                            <!-- Se muestra el nombre del repuesto desde la relación definida en DetalleRepuestoDispositivoOt -->
                                                            <td>{{ $repuestoDispositivo->repuesto->nombre_repuesto }}</td>
                                                            <!-- La observación se almacena en el campo 'descripcion' -->
                                                            <td>{{ $repuestoDispositivo->observacion_repuesto }}</td>
                                                            <td class="d-flex justify-content-center" style="gap: 4px;">
                                                                <!-- Botón para abrir el modal de edición -->
                                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editarModal-{{ $repuestoDispositivo->id }}">
                                                                    <i class="fas fa-edit"></i> Editar
                                                                </button>
                                                                
                                                                <!-- Modal de edición -->
                                                                <div class="modal fade" id="editarModal-{{ $repuestoDispositivo->id }}" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel-{{ $repuestoDispositivo->id }}" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <form action="{{ route('editar.repuesto', ['id' => $repuestoDispositivo->id]) }}" method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                            
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="editarModalLabel-{{ $repuestoDispositivo->id }}">
                                                                                        Editar Observación: {{ $repuestoDispositivo->repuesto->nombre_repuesto }}
                                                                                    </h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <!-- Input hidden con el ID del repuesto asignado -->
                                                                                    <input type="hidden" name="repuesto_id" value="{{ $repuestoDispositivo->repuesto->id }}">
                                                            
                                                                                    <!-- Campo de texto para la observación -->
                                                                                    <div class="form-group">
                                                                                        <label for="observacion-{{ $repuestoDispositivo->id }}">Observación</label>
                                                                                        <input type="text" class="form-control" id="observacion-{{ $repuestoDispositivo->id }}" name="observacion" value="{{ $repuestoDispositivo->observacion_repuesto }}" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
        
                                                                <form id="delete-form-{{ $repuestoDispositivo->id }}" action="{{ route('eliminar.repuesto', ['id' => $repuestoDispositivo->id]) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn btn-danger" onclick="confirmarEliminacion({{ $repuestoDispositivo->id }})">
                                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                                    </button>
                                                                </form>
    
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @empty
                                                    <tr>
                                                        <td colspan="4">No existen repuestos asignados</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>




@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "No podrás revertir esta acción",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + id).submit();
            }
        });
    }
</script>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@endsection
