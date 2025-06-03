@extends('layouts.master')

@section('content')

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Detalle de la Subcategoría -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h2>Detalle de la Subcategoría</h2>
                        <a href="{{ route('parametros.index') }}" class="btn btn-custom-secondary">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                    </div>

                    <!-- Información de la Subcategoría -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Subcategoría: {{ $subcategoria->nombre_subcategoria }}
                        </div>
                        <div class="card-body">
                            <p><strong>Nombre:</strong> {{ $subcategoria->nombre_subcategoria }}</p>
                            <p><strong>Categoría:</strong>
                                @if($subcategoria->categoria)
                                    <a href="{{ route('categoria.show', $subcategoria->categoria->id) }}">
                                        {{ $subcategoria->categoria->nombre_categoria }}
                                    </a>
                                @else
                                    Sin subcategoría
                                @endif
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('parametros.index') }}" class="btn btn-custom-primary">
                                    <i class="fas fa-list"></i> Volver a la lista
                                </a>
                                <a href="{{ route('subcategoria.edit', $subcategoria->id) }}" class="btn btn-custom-warning ml-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('lineas.create', $subcategoria->id) }}" class="btn btn-sm btn-custom-primary mt-3">Agregar Línea</a>
                    <!-- Lista de Líneas -->
                    @if($subcategoria->lineas && $subcategoria->lineas->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <p><strong>Líneas asociadas:</strong></p>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach($subcategoria->lineas as $linea)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $linea->nombre_linea }}
                                            <div>
                                                <a href="{{ route('lineas.show', $linea->id) }}" class="btn btn-sm btn-custom-info">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="{{ route('lineas.edit', $linea->id) }}" class="btn btn-sm btn-custom-warning">
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
                            No hay líneas asignadas a esta subcategoría.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')

    @if(session('subcategoria_nombre') && session('categoria_nombre'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Subcategoría Creada',
                text: "La subcategoría '{{ session('subcategoria_nombre') }}' ha sido creada y asignada a la categoría '{{ session('categoria_nombre') }}' correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection