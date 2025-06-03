@extends('layouts.master')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Editar Servicio -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Servicio</h2>
                </div>
                <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading"><strong>Importante</strong></h5>
                    <p>Si los selectores relacionados con la subcategoría, línea o sublínea se encuentran deshabilitados, es porque se debe ingresar una relación correcta, empezando por la subcategoría.</p>
                </div>
                <!-- Formulario de Edición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Editar Información del Servicio
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito con SweetAlert2 -->
                        @if(session('success'))
                        <div id="success-message-edit" class="d-none">
                            <span id="success-type">{{ session('success_type', 'editar') }}</span>
                            <span id="module-name">Servicio</span>
                            <span id="redirect-url">{{ route('servicios.index') }}</span>
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

                        <form action="{{ route('servicios.update', $servicio->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nombre_servicio">Nombre del Servicio</label>
                                <input type="text" name="nombre_servicio" id="nombre_servicio" class="form-control" value="{{ old('nombre_servicio', $servicio->nombre_servicio) }}" required>
                                @error('nombre_servicio')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="cod_tipo_servicio">Tipo de Servicio</label>
                                <select name="cod_tipo_servicio" id="cod_tipo_servicio" class="form-control" required>
                                    <option value="" disabled>Seleccione un tipo de servicio</option>
                                    @foreach($tiposServicio as $tipo)
                                    <option value="{{ $tipo->id }}" {{ (old('cod_tipo_servicio', $servicio->cod_tipo_servicio) == $tipo->id) ? 'selected' : '' }}>
                                        {{ optional($tipo)->descripcion_tipo_servicio }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('cod_tipo_servicio')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

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
                            
                            
                            <div class="form-group">
                                <label for="cod_subcategoria">Subcategoría</label>
                                <select name="cod_subcategoria" id="cod_subcategoria" class="form-control select2" style="width:100%">
                                    <option value="" disabled selected>Seleccione una subcategoría</option>
                                    @foreach($subcategorias as $subcategoria)
                                        <option value="{{ $subcategoria->id }}" 
                                        {{ (old('cod_subcategoria', $servicio->sublinea && $servicio->sublinea->linea && $servicio->sublinea->linea->subcategoria ?  $servicio->sublinea->linea->cod_subcategoria : null) == $subcategoria->id) ? 'selected' : '' }}
                                        >
                                            {{ $subcategoria->nombre_subcategoria }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_subcategoria')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="cod_linea">Línea</label>
                                <select name="cod_linea" id="cod_linea" class="form-control select2" style="width:100%" 
                                {{ is_null($servicio->sublinea) || is_null($servicio->sublinea->linea) || is_null($servicio->sublinea->linea->subcategoria) || is_null($servicio->sublinea->linea->subcategoria->categoria) ? 'disabled' : '' }}>
                                    <option value="" disabled selected>Seleccione una línea</option>
                                    @foreach($lineas as $linea)
                                        <option value="{{ $linea->id }}" 
                                        {{ (old('cod_linea', $servicio->sublinea && $servicio->sublinea->linea ? $servicio->sublinea->linea->id : null) == $linea->id) ? 'selected' : '' }}
                                        >
                                            {{ $linea->nombre_linea }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_linea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="cod_sublinea">Sublínea</label>
                                <select name="cod_sublinea" id="cod_sublinea" class="form-control select2" style="width:100%" 
                                {{ is_null($servicio->sublinea) || is_null($servicio->sublinea->linea) || is_null($servicio->sublinea->linea->subcategoria) || is_null($servicio->sublinea->linea->subcategoria->categoria) ? 'disabled' : '' }}>
                                    <option value="" disabled selected>Seleccione una sublinea</option>
                                    @foreach($sublineas as $sublinea)
                                        <option value="{{ $sublinea->id }}" {{ (old('cod_sublinea', $servicio->cod_sublinea ? $servicio->cod_sublinea : null) == $sublinea->id) ? 'selected' : '' }}>
                                            {{ $sublinea->nombre_sublinea }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_sublinea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{--
                            <div class="form-group">
                                    <label for="cod_sublinea">Sublínea</label>
                                    <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" id="dropdownButtonSublinea">
                                        @php
                                            // Establecer el nombre del servicio seleccionado por defecto
                                            $sublineaSeleccionada = $sublineas->firstWhere('id', $servicio->cod_sublinea);
                                            echo $sublineaSeleccionada ? $sublineaSeleccionada->nombre_sublinea : 'Seleccione una Sublínea';
                                        @endphp
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        id="dropdownMenuSublinea">
                                        <!-- input de busqueda -->
                                        <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                            <input type="text" class="form-control" id="searchSublinea"
                                                placeholder="Buscar Sublinea..." autocomplete="off">
                                        </div>
                                        <!-- lista de opciones -->
                                        <div class="inner">
                                            @foreach($sublineas as $sublinea)
                                                <a class="dropdown-item" data-value="{{ $sublinea->id }}"
                                                    onclick="selectSublinea('{{ $sublinea->id }}', '{{ $sublinea->nombre_sublinea }}')">
                                                    {{ $sublinea->nombre_sublinea }}
                                                </a>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="cod_sublinea" id="cod_sublinea" value="{{ old('cod_sublinea', $servicio->cod_sublinea) }}">
                                    </div>
                                    @error('cliente_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                --}}
                                {{--
                                <script>

                                    function selectSublinea(sublineaId, sublineaName) {

                                        $("#dropdownButtonSublinea").html(sublineaName);

                                        $("#cod_sublinea").val(sublineaId);

                                        $(".dropdown-menu").removeClass("show");
                                    }

                                    $("#searchSublinea").on("input", function () {
                                        var searchTerm = $(this).val().toLowerCase();

                                        $("#dropdownMenuSublinea .dropdown-item").each(function () {
                                            var sublineaName = $(this).text().toLowerCase();

                                            if (sublineaName.indexOf(searchTerm) === -1) {
                                                $(this).hide();
                                            } else {
                                                $(this).show();
                                            }

                                        });
                                    });

                                    $(document).on("click", function (e) {
                                        if (!$(e.target).closest('.dropdown').length) {
                                            $('.dropdown-menu').removeClass('show');
                                        }
                                    });

                                    $('#searchSublinea').on('click', function (e) {
                                        e.stopPropagation();
                                    });


                                </script>
                                --}}
                            <hr>
                            <div class="form-group" id="categoria-container" style="display: none;">
                                <label for="cod_categoria">Categoría de los dispositivos del servicio</label>
                                <select name="cod_categoria" id="cod_categoria" class="form-control select2" required style="width:100%">
                                    <option value="" disabled>Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ (old('cod_categoria', $servicio->categoriasEquipos->pluck('id')->first()) == $categoria->id) ? 'selected' : '' }}>
                                            {{ $categoria->nombre_categoria }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_categoria')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('servicios.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('.select2').select2({
            placeholder: "Seleccione una opción...",
            allowClear: true
        });

        // Función para mostrar/ocultar el selector de categorías y manejar la validación
        function toggleCategoriaSelector() {
            const tipoServicioId = $('#cod_tipo_servicio').val();
            if (tipoServicioId == 2) { // ID 2 es el que requiere dispositivo
                $('#categoria-container').show();
                $('#cod_categoria').attr('required', true); // Hacer que el campo sea requerido
            } else {
                $('#categoria-container').hide();
                $('#cod_categoria').val(null).trigger('change'); // Limpiar el valor del select2
                $('#cod_categoria').removeAttr('required'); // Quitar el atributo requerido
            }
        }

        // Escuchar cambios en el tipo de servicio
        $('#cod_tipo_servicio').change(function() {
            toggleCategoriaSelector();
        });

        // Llamar a la función al cargar la página para establecer el estado inicial
        toggleCategoriaSelector();
        $('#cod_subcategoria').change(function() {
            var subcategoriaId = $(this).val();
            $('#cod_linea').prop('disabled', false); // Habilitar el selector de línea
            $('#cod_sublinea').empty().append('<option value="" disabled selected>Seleccione una sublínea</option>').prop('disabled', true);
            // Obtener líneas asociadas a la subcategoría seleccionada
            $.ajax({
                url: '/lineasx/' + subcategoriaId,
                type: 'GET',
                success: function(data) {
                    $('#cod_linea').empty().append('<option value="" disabled selected>Seleccione una línea</option>');
                    $.each(data, function(index, linea) {
                        $('#cod_linea').append('<option value="' + linea.id + '">' + linea.nombre_linea + '</option>');
                    });
                }
            });
        });
        
        $('#cod_linea').change(function() {
            var lineaId = $(this).val();
            $('#cod_sublinea').prop('disabled', false); // Habilitar el selector de sublínea
            // Obtener sublíneas asociadas a la línea seleccionada
            $.ajax({
                url: '/sublineasx/' + lineaId,
                type: 'GET',
                success: function(data) {
                    $('#cod_sublinea').empty().append('<option value="" disabled selected>Seleccione una sublínea</option>')
                    $.each(data, function(index, sublinea) {
                        $('#cod_sublinea').append('<option value="' + sublinea.id + '">' + sublinea.nombre_sublinea + '</option>');
                    });
                }
            });
        });
    });
</script>
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
@endsection
