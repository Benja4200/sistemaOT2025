@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Asignar repuestos a Modelo</h2>
                    <a href="{{ route('modelos.index') }}" class="btn btn-secondary" style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
                <!-- Formulario de Asignacion -->
                <div class="card mt-3">
                    <div class="card-header">
                        Asignar repuestos al modelo {{ $modelo->nombre_modelo }}
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito -->
                        @if(session('success'))
                        <div id="success-message" class="d-none">
                            <span id="success-type">{{ session('success_type', 'agregar') }}</span>
                            <span id="module-name">Modelo</span>
                            <span id="redirect-url">{{ route('modelos.index') }}</span>
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

                        <form action="{{ route('modelos.store_repuestos', $modelo->id) }}" method="POST">
                            @csrf
                            
                            <!-- Selector de Repuestos -->
                            <div class="form-group">
                                <div>
                                    <label for="repuestos"><strong>Repuestos</strong></label>
                                </div>
                                <select name="repuestos[]" id="repuestos" class="form-control select2" multiple required>
                                    @foreach($repuestos as $repuesto)
                                        <option value="{{ $repuesto->id }}"
                                            @if($repuestosAsignadosId->contains($repuesto->id)) selected @endif>
                                            {{ $repuesto->nombre_repuesto }} - {{ $repuesto->part_number_repuesto }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('repuestos')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
                                </button>

                                <!-- Botón Cancelar -->
                                <a href="{{ route('modelos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                       <div class="card mt-3">
                    <div class="card-header">
                        Repuestos Relacionados
                    </div>
                    <div class="card-body table-responsive">
                        @if ($repuestosAsignados->isEmpty())
                        <p>No hay repuestos relacionados con este modelo.</p>
                        @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Número de Parte</th>
                                    <th>Nombre Repuesto</th>
                                    <th>Descripción Repuesto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($repuestosAsignados as $repuesto)
                                <tr>
                                    <td>{{ $repuesto->part_number_repuesto }}</td>
                                    <td>{{ $repuesto->nombre_repuesto  }}</td>
                                    <td>{{ $repuesto->descripcion_repuesto  }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Incluye el archivo CSS y JavaScript de Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Inicializar Select2 -->
<script>
    $(document).ready(function() {
        $('#repuestos').select2({
            placeholder: 'Seleccione los repuestos',
            allowClear: true,
            language: 'es',
        });
    });
</script>

<!-- Incluye el archivo JavaScript -->
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
<script src="{{ asset('assets/js/modelos/filtromodelos.js') }}"></script>

@endsection