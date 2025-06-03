@extends('layouts.master')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Agregar Servicio -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Agregar Servicio</h2>
                </div>

                <!-- Sección Tutorial -->
                <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading">Tutorial</h5>
                    <p>Agregue la siguiente información para agregar un servicio correctamente:</p>
                    <ul>
                        <li><strong>Nombre del Servicio:</strong> Nombre del servicio que se está creando.</li>
                        <li><strong>Tipo de Servicio:</strong> Selecciona el tipo de servicio asociado.</li>
                        <li><strong>Sublinea:</strong> Selecciona la sublínea correspondiente.</li>
                        <li><strong>Categoría de los dispositivos del servicio:</strong> Selecciona la categoria de los dispositivos asociados al servicio (Esto es el filtro que se usa al momento de crear la orden de trabajo para filtrar los dispositivos de la sucursal de forma automatica), solo aplica si es que el servicio requiere dispositivos.</li>

                    </ul>
                </div>

                <!-- Formulario de Adición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Agregar Información del Servicio
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito con SweetAlert2 -->
                        @if(session('success'))
                            <div id="success-message" class="d-none">
                                <span id="success-type">agregar</span>
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

                        <form action="{{ route('servicios.store') }}" method="POST">
                            @csrf

                            <!-- Información del Servicio -->
                            <div class="form-group">
                                <label for="nombre_servicio">Nombre del Servicio</label>
                                <input type="text" name="nombre_servicio" id="nombre_servicio" class="form-control"
                                    value="{{ old('nombre_servicio') }}" required>
                                @error('nombre_servicio')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="cod_tipo_servicio">Tipo de Servicio</label>
                                <select name="cod_tipo_servicio" id="cod_tipo_servicio" class="form-control" required>
                                    <option value="" disabled selected>Seleccione un tipo de servicio</option>
                                    @foreach($tiposServicio as $tipo)
                                        <option value="{{ $tipo->id }}">{{ optional($tipo)->descripcion_tipo_servicio }}</option>
                                    @endforeach
                                </select>
                                @error('cod_tipo_servicio')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            {{-- 
                            <div class="form-group">
                                <label for="cod_sublinea">Sublínea</label>
                                <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" id="dropdownButtonSublinea">
                                    Seleccione una Sublínea
                                </button>
                                
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuSublinea">
                                    <!-- input de busqueda -->
                                    <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                        <input type="text" class="form-control" id="searchSublinea"
                                            placeholder="Buscar Sublinea..." autocomplete="off">
                                    </div>
                                    <!-- lista de opciones -->
                                    <div class="inner">
                                        @foreach($sublineas as $sublinea)
                                            <a class="dropdown-item" data-value="{{ $sublinea->id }}" onclick="selectSublinea('{{ $sublinea->id }}', '{{ $sublinea->nombre_sublinea }}')">
                                                {{ $sublinea->nombre_sublinea }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <input type="hidden" name="cod_sublinea" id="cod_sublinea">
                                </div>

                                @error('cod_sublinea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            --}}
                            
                            <div class="form-group">
                                <label for="cod_subcategoria">Subcategoría</label>
                                <select name="cod_subcategoria" id="cod_subcategoria" class="form-control select2" style="width:100%">
                                    <option value="" disabled selected>Seleccione una subcategoría</option>
                                    @foreach($subcategorias as $subcategoria)
                                        <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre_subcategoria }}</option>
                                    @endforeach
                                </select>
                                @error('cod_subcategoria')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="cod_linea">Línea</label>
                                <select name="cod_linea" id="cod_linea" class="form-control select2" disabled style="width:100%">
                                    <option value="" disabled selected>Seleccione una línea</option>
                                    @foreach($lineas as $linea)
                                        <option value="{{ $linea->id }}" data-subcategoria="{{ $linea->cod_subcategoria }}">{{ $linea->nombre_linea }}</option>
                                    @endforeach
                                </select>
                                @error('cod_linea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                            <div class="form-group">
                                <label for="cod_sublinea">Sublínea</label>
                                <select name="cod_sublinea" id="cod_sublinea" class="form-control select2" disabled style="width:100%">
                                    <option value="" disabled selected>Seleccione una sublinea</option>
                                    @foreach($sublineas as $sublinea)
                                        <option value="{{ $sublinea->id }}" data-linea="{{ $sublinea->cod_linea }}">{{ $sublinea->nombre_sublinea }}</option>
                                    @endforeach
                                </select>
                                @error('cod_sublinea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <hr>
                            <div class="form-group" id="categoria_servicio_div">
                                <label for="cod_categoria">Categoría de los dispositivos del servicio</label>
                                <select name="cod_categoria" id="cod_categoria" class="form-control select2" >
                                    <option value="" disabled selected>Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre_categoria }}</option>
                                    @endforeach
                                </select>
                                @error('cod_categoria')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <script>
                                $(document).ready(function() {
                                    // Inicializar Select2
                                    $('.select2').select2({
                                        placeholder: "Seleccione una opción...",
                                        allowClear: true
                                    }).css("width", "100%"); // Establecer el ancho al 100%

                                    // Ocultar el div de categorías al cargar la página
                                    $('#categoria_servicio_div').hide();

                                    // Función para mostrar/ocultar el selector de categorías y manejar la validación
                                    function toggleCategoriaSelector() {
                                        const tipoServicioId = $('#cod_tipo_servicio').val();
                                        if (tipoServicioId == 2) { // ID 2 es el que requiere dispositivo
                                            $('#categoria_servicio_div').show();
                                            $('#cod_categoria').attr('required', true); // Hacer que el campo sea requerido
                                        } else {
                                            $('#categoria_servicio_div').hide();
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
                                    // Al cambiar la subcategoría
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
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
@endsection