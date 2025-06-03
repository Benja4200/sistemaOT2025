@extends('layouts.master')

@section('content')

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Detalle de la Línea -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h2>Detalle de la Línea</h2>
                        <a href="{{ route('parametros.index') }}" class="btn btn-custom-secondary">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                    </div>

                    <!-- Información de la Línea -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Línea: {{ $linea->nombre_linea }}
                        </div>
                        <div class="card-body">
                            <p><strong>Nombre:</strong> {{ $linea->nombre_linea }}</p>
                            <p><strong>Subcategoría:</strong>
                                @if($linea->subcategoria)
                                    <a href="{{ route('subcategoria.show', $linea->subcategoria->id) }}">
                                        {{ $linea->subcategoria->nombre_subcategoria }}
                                    </a>
                                @else
                                    Sin subcategoría
                                @endif
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('parametros.index') }}" class="btn btn-custom-primary">
                                    <i class="fas fa-list"></i> Volver a la lista
                                </a>
                                <a href="{{ route('lineas.edit', $linea->id) }}" class="btn btn-custom-warning ml-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                        </div>
                    </div>
                     <a href="{{ route('sublineas.create', $linea->id) }}" class="btn btn-sm btn-custom-primary mt-3">Agregar Sublinea</a>
                    <!-- Lista de Sublíneas -->
                    @if($linea->sublines && $linea->sublines->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <p><strong>Sublínea(s): </strong></p>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach($linea->sublines as $sublinea)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $sublinea->nombre_sublinea }}
                                            <div>
                                                <a href="{{ route('sublineas.show', $sublinea->id) }}" class="btn btn-sm btn-custom-info">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="{{ route('sublineas.edit', $sublinea->id) }}" class="btn btn-sm btn-custom-warning">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mt-3">
                            No hay sublíneas asignadas a esta línea.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')

    @if(session('linea_nombre') && session('subcategoria_nombre'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Línea Creada',
                text: "La línea '{{ session('linea_nombre') }}' ha sido creada y asignada a la subcategoría '{{ session('subcategoria_nombre') }}' correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection
    