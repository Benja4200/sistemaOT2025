@extends('layouts.master')

@section('content')

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Detalle de la Categoría -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h2>Detalle de la Categoría</h2>
                        <a href="{{ route('parametros.index') }}" class="btn btn-custom-secondary">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                    </div>

                    <!-- Información de la Categoría -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Categoría: {{ $categoria->nombre_categoria }}
                        </div>
                        <div class="card-body">
                            <p><strong>Nombre:</strong> {{ $categoria->nombre_categoria }}</p>
                            <a href="{{ route('parametros.index') }}" class="btn btn-custom-primary">
                                <i class="fas fa-list"></i> Volver a la lista
                            </a>
                            <a href="{{ route('categoria.edit', $categoria->id) }}" class="btn btn-custom-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('subcategoria.create', $categoria->id)  }}" class="btn btn-sm btn-custom-primary mt-3">Agregar Subcategoría</a>

                    <!-- Lista de Subcategorías -->
                    @if($categoria->subcategorias && $categoria->subcategorias->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <p><strong>Subcategoría(s): </strong>{{ $categoria->nombre_categoria }}</p>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach($categoria->subcategorias as $subcategoria)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $subcategoria->nombre_subcategoria }}
                                            <div>
                                                <a href="{{ route('subcategoria.show', $subcategoria->id) }}" class="btn btn-sm btn-custom-info">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="{{ route('subcategoria.edit', $subcategoria->id) }}" class="btn btn-sm btn-custom-warning">
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
                            No hay subcategorías asignadas a esta categoría.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </main>
    
    
@endsection

@section('scripts')

    @if(session('categoria_nombre'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Categoría Creada',
                text: "La categoría '{{ session('categoria_nombre') }}' ha sido creada correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection