@extends('layouts.master')

@section('content')
<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Agregar Marca -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Repuesto</h2>
                </div>
                
                {{--
                <!-- Sección Tutorial -->
                <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading">Tutorial</h5>
                    <p>Agregue la siguiente información para registrar un nuevo repuesto:</p>
                    <ul>
                        <li><strong>Nombre del repuesto:</strong> Ingrese el nombre del repuesto.</li>
                    </ul>
                </div>
                --}}
                <!-- Formulario de Adición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Editar Información del Repuesto
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito con SweetAlert2 -->
                        @if(session('success'))
                        <div id="success-message" class="d-none">
                            <span id="success-type">{{ session('success_type', 'agregar') }}</span>
                            <span id="module-name">Marca</span>
                            <span id="redirect-url">{{ route('marcas.index') }}</span>
                        </div>
                        @endif

                        <!-- Mensaje de error -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('repuestos.update', $repuesto->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- Nombre de la Marca -->
                            <div class="form-group">
                                <label for="nombre_repuesto">Nombre del Repuesto</label>
                                <input type="text" name="nombre_repuesto" id="nombre_repuesto" class="form-control" value="{{ old('nombre_repuesto', $repuesto->nombre_repuesto) }}" required>
                                @error('nombre_repuesto')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="descripcion_repuesto">Descripción del Repuesto</label>
                                <input type="text" name="descripcion_repuesto" id="descripcion_repuesto" class="form-control" value="{{ old('descripcion_repuesto',$repuesto->descripcion_repuesto) }}" required>
                                @error('descripcion_repuesto')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="part_number_repuesto">Número de parte del Repuesto</label>
                                <input type="text" name="part_number_repuesto" id="part_number_repuesto" class="form-control" value="{{ old('part_number_repuesto',$repuesto->part_number_repuesto) }}" required>
                                @error('part_number_repuesto')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
                                </button>

                                <!-- Botón Cancelar -->
                                <a href="{{ route('repuestos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Incluye el archivo JavaScript -->
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>

@endsection
