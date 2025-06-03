@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Detalle de la Tarea -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Detalle de la Tarea</h2>
                    <a href="{{ route('tareas.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <!-- Información de la Tarea -->
                <div class="card mt-3">
                    <div class="card-header">
                        Información de la Tarea
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre de la Tarea</th>
                                    {{-- <th>Tiempo de Tarea (horas)</th> --}}
                                    {{-- <th>Servicio Asociado</th> --}}
                                    <th>Nombre de la Subcategoria</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $tarea->nombre_tarea }}</td>
                                    {{-- <td>{{ $tarea->tiempo_tarea }}</td> --}}
                                    {{-- <td>{{ $tarea->servicio->nombre_servicio ?? 'No asignado' }}</td> --}}
                                    <td>{{ optional($tarea->subcategoria)->nombre_subcategoria }}</td> 
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Información del Servicio Asociado -->
                @if($tarea->servicio)
                <div class="card mt-3">
                    <div class="card-header">
                        Información del Servicio Asociado
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre Servicio</th>
                                    <th>Tipo de Servicio</th>
                                    <th>Sublínea</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $tarea->servicio->nombre_servicio }}</td>
                                    <td>{{ $tarea->servicio->tipoServicio->descripcion_tipo_servicio ?? 'No asignado' }}</td>
                                    <td>{{ $tarea->servicio->sublinea->nombre_sublinea ?? 'No asignada' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="card mt-3">
                    <div class="card-header">
                        Información del Servicio Asociado
                    </div>
                    <div class="card-body">
                        <p>No hay servicio asociado.</p>
                    </div>
                </div>
                @endif

                <!-- Acciones -->
                {{--
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>

                    <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?');">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
                --}}
            </div>
        </div>
    </div>
</main>
@endsection
