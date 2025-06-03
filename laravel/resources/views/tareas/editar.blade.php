@extends('layouts.master')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Editar Tarea -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Tarea</h2>
                </div>

                <!-- Formulario de Edición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Editar Información de la Tarea
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito -->
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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

                        <form action="{{ route('tareas.update', $tarea->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nombre_tarea">Nombre de la Tarea</label>
                                <input type="text" name="nombre_tarea" id="nombre_tarea" class="form-control" value="{{ old('nombre_tarea', $tarea->nombre_tarea) }}" required>
                                @error('nombre_tarea')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="cod_subcategoria" class="form-label">Subcategorias</label>
                                <select class="form-select form-control select2" id="cod_subcategoria" name="cod_subcategoria" style="width: 100%;" required>
                                    <option value="">Seleccione una subcategoria</option>
                                    @foreach ($subcategorias as $subcategoria)
                                        @if ($subcategoria->id == optional($tarea->subcategoria)->id)
                                            <option value="{{ $subcategoria->id }}" selected>
                                                {{ html_entity_decode($subcategoria->nombre_subcategoria) }}
                                            </option>
                                        @else
                                            <option value="{{ $subcategoria->id }}">
                                                {{ html_entity_decode($subcategoria->nombre_subcategoria) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('cod_subcategoria')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Requiere observación?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="requiere_obs" id="observacion_si" value="Si" {{ old('requiere_obs',$tarea->requiere_obs) == 'Si' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="observacion_si">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="requiere_obs" id="observacion_no" value="No" {{ old('requiere_obs',$tarea->requiere_obs) == 'No' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="observacion_no">No</label>
                                    </div>

                                @error('requiere_observacion')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- 
                            <div class="form-group">
                                <label for="tiempo_tarea">Tiempo de la Tarea (en minutos)</label>
                                <input type="number" name="tiempo_tarea" id="tiempo_tarea" class="form-control" value="{{ old('tiempo_tarea', $tarea->tiempo_tarea) }}" required>
                                @error('tiempo_tarea')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            --}}
                            <style>
                                /* espacio entre los selectores */
                                .form-group {
                                    margin-bottom: 20px;
                                }

                                .dropdown-menu {
                                    max-height: 300px;
                                    overflow-y: auto;
                                }
                            </style>
                            
                            {{-- 
                            <div class="form-group">
                                <label for="cod_sublinea">Servicio</label>
                            
                                <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonServicio">
                                    @php
                                        // Establecer el nombre del servicio seleccionado por defecto
                                        $servicioSeleccionado = $servicios->firstWhere('id', $tarea->cod_servicio);
                                        echo $servicioSeleccionado ? $servicioSeleccionado->nombre_servicio : 'Seleccione un Servicio';
                                    @endphp
                                </button>
                            
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuServicio">
                                    <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                        <input type="text" class="form-control" id="searchServicio" placeholder="Buscar Servicio..." autocomplete="off">
                                    </div>
                                    
                                    <div class="inner">
                                        @foreach($servicios as $servicio)
                                            <a class="dropdown-item" data-value="{{ $servicio->id }}" onclick="selectServiciox('{{ $servicio->id }}', '{{ $servicio->nombre_servicio }}')">
                                                {{ $servicio->nombre_servicio }}
                                            </a>
                                        @endforeach
                                    </div>
                            
                                    <input type="hidden" name="cod_servicio" id="cod_servicio" value="{{ old('cod_servicio', $tarea->cod_servicio) }}">
                                </div>
                            
                                @error('cod_servicio')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            --}}
                            <script>
                                
                                function selectServiciox(servicioId, servicioName) {
                                    
                                    $("#dropdownButtonServicio").html(servicioName);
                                    
                                    $("#cod_servicio").val(servicioId);
                                    
                                    $(".dropdown-menu").removeClass("show");
                                }

                                $("#searchServicio").on("input", function() {
                                    var searchTerm = $(this).val().toLowerCase();

                                    $("#dropdownMenuServicio .dropdown-item").each(function() {
                                        var servicioName = $(this).text().toLowerCase();

                                        if (servicioName.indexOf(searchTerm) === -1) {
                                            $(this).hide();
                                        } else {
                                            $(this).show();
                                        }
                                    });
                                });

                                $(document).on("click", function(e) {
                                    if (!$(e.target).closest('.dropdown').length) {
                                        $('.dropdown-menu').removeClass('show');
                                    }
                                });

                                $('#searchServicio').on('click', function(e) {
                                    e.stopPropagation();
                                });
                            </script>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('tareas.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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

<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Seleccione una subcategoria",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados"; // Mensaje personalizado
                    },
                }
            });
        });
    </script>
@endsection

