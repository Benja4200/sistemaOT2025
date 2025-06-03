@extends('layouts.master')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                 
                    
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Agregar Dispositivo</h2>
                    <a href="{{ route('ordenes.create') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>
                {{--
                <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading">Tutorial</h5>
                    <p>Agregue la siguiente información para agregar un dispositivo correctamente:</p>
                    <ul>
                        <li><strong>Número de Serie:</strong> Ingrese el número de serie del dispositivo.</li>
                        <li><strong>Modelo:</strong> Seleccione el modelo correspondiente al dispositivo.</li>
                        <li><strong>Sucursal:</strong> Seleccione la sucursal donde se registrará el dispositivo.</li>
                    </ul>
                </div>
                --}}
                <div class="card mt-3">
                    <div class="card-header">Información del Dispositivo</div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('dispositivos.nuevo') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="numero_serie_dispositivo">Número de Serie</label>
                                <input type="text" name="numero_serie_dispositivo" class="form-control" required>
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
                            
                            <!-- selector modelos con campo de busqueda -->
                            
                            <h5>Filtros para modelos</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cod_categoria">Categoría</label>
                                        <select name="cod_categoria" id="cod_categoria" class="form-control select2" style="width:100%">
                                            <option value="" disabled selected>Seleccione una categoría</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nombre_categoria }}</option>
                                            @endforeach
                                        </select>
                                        @error('cod_categoria')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cod_subcategoria">Subcategoría</label>
                        <select name="cod_subcategoria" id="cod_subcategoria" class="form-control select2" disabled style="width:100%">
                            <option value="" disabled selected>Seleccione una subcategoría</option>
                        </select>
                        @error('cod_subcategoria')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cod_linea">Línea</label>
                        <select name="cod_linea" id="cod_linea" class="form-control select2" disabled style="width:100%">
                            <option value="" disabled selected>Seleccione una línea</option>
                        </select>
                        @error('cod_linea')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cod_sublinea">Sublínea</label>
                        <select name="cod_sublinea" id="cod_sublinea" class="form-control select2" disabled style="width:100%">
                            <option value="" disabled selected>Seleccione una sublínea</option>
                        </select>
                        @error('cod_sublinea')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
                            
                            
                            
                            <div class="form-group">
                                <label for="cod_modelo">Modelo</label>
                                <select name="cod_modelo" id="cod_modelo" class="form-control select2" required style="width:100%">
                                    <option value="">Seleccione un modelo</option>
                                    @foreach($modelos as $modelo)
                                        <option value="{{ $modelo->id }}" 
                                            {{ (old('cod_modelo') == $modelo->id ? 'selected' : '') }}>
                                            {{ $modelo->nombre_modelo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_modelo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                             
                            {{--    
                            <div class="form-group">
                                <label for="cod_modelo">Modelo</label>
                                <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonModelo">
                                    Seleccione un Modelo
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuModelo">
                                    <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                        <input type="text" class="form-control" id="searchModelModelo" placeholder="Buscar modelo..." autocomplete="off">
                                    </div>
                                    <div class="inner">
                                        @foreach($modelos as $modelo)
                                            <a class="dropdown-item" data-value="{{ $modelo->id }}" onclick="selectModel('{{ $modelo->id }}', '{{ $modelo->nombre_modelo }}', 'Modelo')">
                                                {{ $modelo->nombre_modelo }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="cod_modelo" id="cod_modelo">
                            </div>
                            --}}
                            <!-- selector Clientes con campo de busqueda -->
                            <div class="form-group">
                                <label for="cod_cliente">Cliente</label>
                                <select name="cod_cliente" id="cod_cliente" class="form-control select2" required style="width:100%">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                            {{ (old('cod_cliente') == $cliente->id ? 'selected' : '') }}>
                                            {{ html_entity_decode($cliente->nombre_cliente) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_cliente')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{--
                            <div class="form-group">
                                <label for="cod_cliente">Cliente</label>
                                <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonCliente">
                                    Seleccione un Cliente
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuCliente">
                                    <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                        <input type="text" class="form-control" id="searchModelCliente" placeholder="Buscar cliente..." autocomplete="off">
                                    </div>
                                    <div class="inner">
                                        @foreach($clientes as $cliente)
                                            <a class="dropdown-item" data-value="{{ $cliente->id }}" onclick="selectModel('{{ $cliente->id }}', '{{ $cliente->nombre_cliente }}', 'Cliente')">
                                                {{ $cliente->nombre_cliente }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="cod_cliente" id="cod_cliente">
                            </div>
                            --}}
                            <!-- selector Sucursales con campo de busqueda -->
                            
                            <div class="form-group">
                                <label for="cod_sucursal">Sucursal</label>
                                <select name="cod_sucursal" id="cod_sucursal" class="form-control select2"  disabled required style="width:100%">
                                    <option value="">Seleccione una sucursal</option>
                                    {{--
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                            {{ (old('cod_sucursal') == $cliente->id ? 'selected' : '') }}>
                                            {{ $cliente->nombre_cliente }}
                                        </option>
                                    @endforeach
                                    --}}
                                </select>
                                @error('cod_sucursal')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{--
                            <div class="form-group">
                                <label for="cod_sucursal">Sucursal</label>
                                <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonSucursal" disabled>
                                    Seleccione una Sucursal
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuSucursal">
                                    <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                        <input type="text" class="form-control" id="searchModelSucursal" placeholder="Buscar sucursal..." autocomplete="off">
                                    </div>
                                    <div class="inner">
                                        <!-- Las sucursales se llenarán dinámicamente -->
                                    </div>
                                </div>
                                <input type="hidden" name="cod_sucursal" id="cod_sucursal">
                            </div>
                            --}}
                            <script>
                                // Función para seleccionar el modelo y actualizar el botón con el texto seleccionado
                                function selectModel(modelId, modelName, type) {
                                    // Si es Modelo
                                    if (type === 'Modelo') {
                                        $("#dropdownButtonModelo").html(modelName);
                                        $("#cod_modelo").val(modelId);
                                    }
                                    // Si es Cliente
                                    else if (type === 'Cliente') {
                                        $("#dropdownButtonCliente").html(modelName);
                                        $("#cod_cliente").val(modelId);
                                        // Limpiar el dropdown de sucursales al cambiar de cliente
                                        $('#dropdownMenuSucursal .inner').empty();
                                        $("#cod_sucursal").val(''); // Limpiar el valor oculto de sucursal
                                        $("#dropdownButtonSucursal").html('Seleccione una Sucursal'); // Resetear el texto del botón
                                        $("#dropdownButtonSucursal").prop('disabled', true); // Deshabilitar el botón de sucursales
                                        getSucursales(modelId); // Obtener sucursales del nuevo cliente
                                    }
                                    // Si es Sucursal
                                    else if (type === 'Sucursal') {
                                        $("#dropdownButtonSucursal").html(modelName);
                                        $("#cod_sucursal").val(modelId);
                                    }
                            
                                    // Cerramos el dropdown
                                    $(".dropdown-menu").removeClass("show");
                                }

                                function getSucursales(clienteId) {
                                    $.ajax({
                                        url: '/dispositivos/getSucursales/' + clienteId, // URL actualizada
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(data) {
                                            $('#dropdownMenuSucursal .inner').empty(); // Limpiar el campo de sucursales
                                            $.each(data, function(index, sucursal) {
                                                $('#dropdownMenuSucursal .inner').append('<a class="dropdown-item" data-value="' + sucursal.id + '" onclick="selectModel(\'' + sucursal.id + '\', \'' + sucursal.nombre_sucursal + '\', \'Sucursal\')">' + sucursal.nombre_sucursal + '</a>');
                                            });
                                            // Habilitar el botón de sucursales
                                            $("#dropdownButtonSucursal").prop('disabled', false);
                                        },
                                        error: function(xhr) {
                                            console.error("Error al obtener las sucursales:", xhr);
                                        }
                                    });
                                }
                            
                                // Filtrar las opciones mientras escribes en el input de búsqueda para el modelo
                                $("#searchModelModelo").on("input", function() {
                                    var searchTerm = $(this).val().toLowerCase();
                                    $("#dropdownMenuModelo .dropdown-item").each(function() {
                                        var modelName = $(this).text().toLowerCase();
                                        if (modelName.indexOf(searchTerm) === -1) {
                                            $(this).hide();
                                        } else {
                                            $(this).show();
                                        }
                                    });
                                });
                            
                                // Filtrar las opciones mientras escribes en el input de búsqueda para el cliente
                                $("#searchModelCliente").on("input", function() {
                                    var searchTerm = $(this).val().toLowerCase();
                                    $("#dropdownMenuCliente .dropdown-item").each(function() {
                                        var clienteName = $(this).text().toLowerCase();
                                        if (clienteName.indexOf(searchTerm) === -1) {
                                            $(this).hide();
                                        } else {
                                            $(this).show();
                                        }
                                    });
                                });
                            
                                // Filtrar las opciones mientras escribes en el input de búsqueda para la sucursal
                                $("#searchModelSucursal").on("input", function() {
                                    var searchTerm = $(this).val().toLowerCase();
                                    $("#dropdownMenuSucursal .dropdown-item").each(function() {
                                        var sucursalName = $(this).text().toLowerCase();
                                        if (sucursalName.indexOf(searchTerm) === -1 ) {
                                            $(this).hide();
                                        } else {
                                            $(this).show();
                                        }
                                    });
                                });
                            
                                // Asegurarse de que al hacer clic fuera del dropdown se cierre correctamente
                                $(document).on("click", function(e) {
                                    if (!$(e.target).closest('.dropdown').length) {
                                        $('.dropdown-menu').removeClass('show');
                                    }
                                });
                            
                                // Evitar que el dropdown se cierre al hacer clic en el input de búsqueda
                                $('#searchModelModelo').on('click', function(e) {
                                    e.stopPropagation();
                                });
                            
                                $('#searchModelCliente').on('click', function(e) {
                                    e.stopPropagation();
                                });
                                
                                $('#searchModelSucursal').on('click', function(e) {
                                    e.stopPropagation();
                                });
                            </script>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
                                </button>

                                <a href="{{ route('ordenes.create') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Seleccione una opción",
            allowClear: true,
            language: {
                noResults: function() {
                    return "No se encontraron resultados"; // Mensaje personalizado
                },
            }
        });
        
        $('#cod_cliente').change(function() {
            var clienteId = $(this).val();
            $('#cod_sucursal').prop('disabled', false); // Habilitar el selector de línea
            

            // Obtener líneas asociadas a la subcategoría seleccionada
            $.ajax({
                url: '/dispositivos/getSucursales/' + clienteId,
                type: 'GET',
                success: function(data) {
                    $('#cod_sucursal').empty().append('<option value="" disabled selected>Seleccione una sucursal</option>');
                    $.each(data, function(index, sucursal) {
                        $('#cod_sucursal').append('<option value="' + sucursal.id + '">' + sucursal.nombre_sucursal + '</option>');
                    });
                }
            });
        });
        
        $('#cod_categoria').change(function() {
            var categoriaId = $(this).val();
            $('#cod_subcategoria').prop('disabled', false); // Habilitar el selector de línea
            $('#cod_linea').empty().append('<option value="" disabled selected>Seleccione una sublínea</option>').prop('disabled', true);
            $('#cod_sublinea').empty().append('<option value="" disabled selected>Seleccione una sublínea</option>').prop('disabled', true);

            // Obtener líneas asociadas a la subcategoría seleccionada
            $.ajax({
                url: '/subcategoriasx/' + categoriaId,
                type: 'GET',
                success: function(data) {
                    $('#cod_subcategoria').empty().append('<option value="" disabled selected>Seleccione una línea</option>');
                    $.each(data, function(index, subcategoria) {
                        $('#cod_subcategoria').append('<option value="' + subcategoria.id + '">' + subcategoria.nombre_subcategoria + '</option>');
                    });
                }
            });
            // Obtener modelos correspondientes a la categoría
            $.ajax({
                url: '/dispositivos/getModelosPorCategoria/' + categoriaId,
                type: 'GET',
                success: function(data) {
                    $('#cod_modelo').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
                    $.each(data, function(index, modelo) {
                        $('#cod_modelo').append('<option value="' + modelo.id + '">' + modelo.nombre_modelo + '</option>');
                    });
                    $('#cod_modelo').prop('disabled', false); // Habilitar el selector de modelos
                }
            });
        });
        
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
            // Obtener modelos correspondientes a la categoría
            $.ajax({
                url: '/dispositivos/getModelosPorSubcategoria/' + subcategoriaId,
                type: 'GET',
                success: function(data) {
                    $('#cod_modelo').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
                    $.each(data, function(index, modelo) {
                        $('#cod_modelo').append('<option value="' + modelo.id + '">' + modelo.nombre_modelo + '</option>');
                    });
                    $('#cod_modelo').prop('disabled', false); // Habilitar el selector de modelos
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
            // Obtener modelos correspondientes a la categoría
            $.ajax({
                url: '/dispositivos/getModelosPorLinea/' + lineaId,
                type: 'GET',
                success: function(data) {
                    $('#cod_modelo').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
                    $.each(data, function(index, modelo) {
                        $('#cod_modelo').append('<option value="' + modelo.id + '">' + modelo.nombre_modelo + '</option>');
                    });
                    $('#cod_modelo').prop('disabled', false); // Habilitar el selector de modelos
                }
            });
        });
        
        $('#cod_sublinea').change(function() {
            var sublineaId = $(this).val();
            // Obtener modelos correspondientes a la categoría
            $.ajax({
                url: '/dispositivos/getModelosPorSublinea/' + sublineaId,
                type: 'GET',
                success: function(data) {
                    $('#cod_modelo').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
                    $.each(data, function(index, modelo) {
                        $('#cod_modelo').append('<option value="' + modelo.id + '">' + modelo.nombre_modelo + '</option>');
                    });
                    $('#cod_modelo').prop('disabled', false); // Habilitar el selector de modelos
                }
            });
        });
    });
</script>
@endsection