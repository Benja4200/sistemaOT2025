@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Asignar servicios a Tecnico</h2>
                    <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary" style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
                <!-- Formulario de Asignacion -->
                <div class="card mt-3">
                    <div class="card-header">
                        Asignar servicios al tecnico {{ $tecnico->nombre_tecnico }}
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito -->
                        @if(session('success'))
                        <div id="success-message" class="d-none">
                            <span id="success-type">{{ session('success_type', 'agregar') }}</span>
                            <span id="module-name">Tecnico</span>
                            <span id="redirect-url">{{ route('tecnicos.index') }}</span>
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

                        <form action="{{ route('tecnicos.store_servicios', $tecnico->id) }}" method="POST">
                            @csrf
                            
                            <!-- Selector de Repuestos -->
                            <div class="form-group">
                                <div>
                                    <label for="servicios"><strong>Servicios</strong></label>
                                </div>
                                <select name="servicios[]" id="servicios" class="form-control select2" multiple required>
                                    @foreach($servicios as $servicio)
                                        <option value="{{ $servicio->id }}"
                                            @if($serviciosAsignadosId->contains($servicio->id)) selected @endif>
                                            {{ $servicio->nombre_servicio }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('servicios')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
                                </button>

                                <!-- Botón Cancelar -->
                                <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                       <div class="card mt-3">
                    <div class="card-header">
                        Servicios Relacionados
                    </div>
                    <div class="card-body">
                        @if ($serviciosAsignados->isEmpty())
                        <p>No hay servicios relacionados con este tecnico.</p>
                        @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre Servicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviciosAsignados as $servicio)
                                <tr>
                                    <td>{{ $servicio->nombre_servicio  }}</td>
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
        $('#servicios').select2({
            placeholder: 'Seleccione los servicios',
            allowClear: true,
            language: 'es',
        });
    });
</script>

<!-- Incluye el archivo JavaScript -->
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
<script src="{{ asset('assets/js/modelos/filtromodelos.js') }}"></script>

@endsection