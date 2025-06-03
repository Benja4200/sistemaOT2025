@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Detalle del Servicio -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Detalle del Servicio</h2>
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary"
                        style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <!-- Información del Servicio -->
                <div class="card mt-3">
                    <div class="card-header">
                        Información del Servicio
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre del Servicio</th>
                                    <th>Código Tipo de Servicio</th>
                                    <th>Código Sublinea</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $servicio->nombre_servicio }}</td>
                                    <td>{{ $servicio->cod_tipo_servicio }}</td>
                                    <td>{{ $servicio->cod_sublinea ? $servicio->cod_sublinea : 'n/a' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        Información de la sublinea del servicio
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th>Línea</th>
                                    <th>Sublínea</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $servicio->sublinea ? $servicio->sublinea->linea->subcategoria->categoria->nombre_categoria : 'n/a' }}
                                    </td>
                                    <td>
                                        {{ $servicio->sublinea ? $servicio->sublinea->linea->subcategoria->nombre_subcategoria : 'n/a' }}
                                    </td>
                                    <td>
                                        {{ $servicio->sublinea ? $servicio->sublinea->linea->nombre_linea : 'n/a' }}
                                    </td>
                                    <td>
                                        {{ $servicio->sublinea ? $servicio->sublinea->nombre_sublinea : 'n/a' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                                <div class="card mt-3">
                                    <div class="card-header">
                                        Información de las Tareas Asociadas
                                    </div>
                                    <div class="card-body">
                                    @if ($servicio->tareasServicio->isEmpty())
                                        <p>No hay tareas asociadas a este servicio.</p>
                                    @else
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nombre Tarea</th>
                                                    <th>Tiempo Tarea en minutos</th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($servicio->tareasServicio as $tareaAsignada)
                                                <tr>
                                                    <td>{{ $tareaAsignada->nombre_tarea  }}</td>
                                                    <td>{{ $tareaAsignada->pivot->tiempo  }}</td>
                                                    
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>
{{--
                <!-- Información de las Tareas Asociadas -->
                @if ($servicio->tareas->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        Información de las Tareas Asociadas
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre de la Tarea</th>
                                    <th>Tiempo de la Tarea</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicio->tareas as $tarea)
                                <tr>
                                    <td>{{ $tarea->nombre_tarea }}</td>
                                    <td>{{ $tarea->tiempo_tarea }} min</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="card mt-3">
                    <div class="card-header">
                        Información de las Tareas Asociadas
                    </div>
                    <div class="card-body">
                        <p>No hay tareas asociadas a este servicio.</p>
                    </div>
                </div>
                @endif
                --}}
            </div>
        </div>
    </div>
</main>
@endsection
