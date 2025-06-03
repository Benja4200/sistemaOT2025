@extends('layouts.master')
@section('content')
<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
 <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Actividades Extra OT: {{ $numero_ot }}</h2>
                    <a href="{{ route('misOrdenes') }}"
                       class="btn"
                       style="background-color: #cc0066; border-color: #cc0066; color: #ffffff;">
                      <i class="fa-solid fa-circle-left"></i>
                      <span>Regresar</span>
                    </a>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        Agregar Información del Servicio
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
                        <form action="{{ route('actividad_extra.storeacti') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cod_ot" value="{{ $numero_ot }}">
                    
                            <div class="mb-3">
                                <label for="nombre_actividad" class="form-label">Nombre de la Actividad</label>
                                <input type="text" name="nombre_actividad" id="nombre_actividad" class="form-control" required>
                            </div>
                    
                            <div class="mb-3">
                                <label for="horas_actividad" class="form-label">Horas de la Actividad</label>
                                <input type="number" name="horas_actividad" id="horas_actividad" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
                                </button>
                                <a href="{{ route('misOrdenes') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                            
                        </form>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        Actividades extra de la orden
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Actividad</th>
                                        <th>Horas</th>
                                        <th>Fecha registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ot->actividadesExtra as $actividad)
                                        <tr>
                                            <td>
                                                {{ $actividad->nombre_actividad }}
                                            </td>
                                            <td>
                                                {{ $actividad->horas_actividad }}    
                                            </td>
                                            <td>
                                                {{ $actividad->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="d-flex justify-content-center" style="gap: 4px;">
                                                <!-- Botón para abrir el modal de edición -->
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editarModal-{{ $actividad->id }}">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>
                                                
                                                <!-- Modal de edición -->
                                                <div class="modal fade" id="editarModal-{{ $actividad->id }}" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel-{{ $actividad->id }}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form action="{{ route('actividades-extra.update', $actividad->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                            
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editarModalLabel-{{ $actividad->id }}">
                                                                        Editar Actividad Extra:
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Campo de texto para la observación -->
                                                                    <div class="form-group">
                                                                        <label for="observacion-{{ $actividad->id }}">Nombre de la actividad</label>
                                                                        <input type="text" class="form-control" id="observacion-{{ $actividad->id }}" name="observacion" value="{{ $actividad->nombre_actividad }}" required>
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

                                                <form id="delete-form-{{ $actividad->id }}" action="{{ route('actividades-extra.destroy', $actividad->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn btn-danger" onclick="confirmarEliminacion({{ $actividad->id }})">
                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            Sin actividades extra.
                                        </td>
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
</main>
    

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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