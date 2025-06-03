@extends('layouts.master')

@section('content')

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Detalle de la Sublínea -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h2>Detalle de la Sublínea</h2>
                        <a href="{{ route('parametros.index') }}" class="btn btn-custom-secondary">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                    </div>

                    <!-- Información de la Sublínea -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Sublínea: {{ $sublinea->nombre_sublinea }}
                        </div>
                        <div class="card-body">
                            <p><strong>Nombre:</strong> {{ $sublinea->nombre_sublinea }}</p>
                            <p><strong>Línea:</strong>
                                @if($sublinea->linea)
                                    <a href="{{ route('lineas.show', $sublinea->linea->id) }}">
                                        {{ $sublinea->linea->nombre_linea }}
                                    </a>
                                @else
                                    No asignada
                                @endif
                            </p>
                            <div class="d-flex">
                                <a href="{{ route('parametros.index') }}" class="btn btn-custom-primary">
                                    <i class="fas fa-list"></i> Volver a la lista
                                </a>
                                <!-- Ruta Correcta: 'sublineas.edit' -->
                                <a href="{{ route('sublineas.edit', $sublinea->id) }}" class="btn btn-custom-warning ml-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
@if(session('sublinea_nombre') && session('linea_nombre'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sublínea Creada',
                text: "La sublínea '{{ session('sublinea_nombre') }}' ha sido creada y asignada a la línea '{{ session('linea_nombre') }}' correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection