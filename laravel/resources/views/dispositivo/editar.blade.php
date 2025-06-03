@extends('layouts.master')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Dispositivo</h2>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        Editar Información del Dispositivo
                    </div>
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

                        <form action="{{ route('dispositivos.update', $dispositivo->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="numero_serie_dispositivo">Número de Serie</label>
                                <input type="text" name="numero_serie_dispositivo" id="numero_serie_dispositivo" class="form-control" value="{{ old('numero_serie_dispositivo', $dispositivo->numero_serie_dispositivo) }}" required>
                                @error('numero_serie_dispositivo')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
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
                                    <option value="{{ $modelo->id }}" {{ $modelo->id == $dispositivo->cod_modelo ? 'selected' : '' }}>
                                        {{ $modelo->nombre_modelo }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('cod_modelo')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Selector de Clientes -->
                            <div class="form-group">
                                <label for="cod_cliente">Cliente</label>
                                <select name="cod_cliente" id="cod_cliente" class="form-control select2" required style="width:100%">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                            {{ isset($dispositivo->sucursal) && $cliente->id == $dispositivo->sucursal->cod_cliente ? 'selected' : '' }}>
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
                                    @php
                                        // Establecer el nombre del cliente seleccionado por defecto
                                        echo $cliente->nombre_cliente;
                                    @endphp
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuCliente">
                                    <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                        <input type="text" class="form-control" id="searchModelCliente" placeholder="Buscar cliente..." autocomplete="off">
                                    </div>
                                    <div class="inner">
                                        @foreach($clientes as $cliente)
                                            <a class="dropdown-item" data-value="{{ $cliente->id }}" onclick="selectModel('{{ $cliente->id }} ', '{{ $cliente->nombre_cliente }}')">
                                                {{ $cliente->nombre_cliente }}
                                            </a>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="cod_cliente" id="cod_cliente" value="{{ old('cod_cliente', $cliente->id) }}">
                                    @error('cod_cliente')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            --}}
                            <!-- Selector de Sucursales -->
                            
                            <div class="form-group">
                                <label for="cod_sucursal">Sucursal</label>
                                <select name="cod_sucursal" id="cod_sucursal" class="form-control select2" {{ isset($dispositivo->sucursal) ? '' : 'disabled' }} required style="width:100%">
                                    <option value="">Seleccione una sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}" 
                                            {{ $sucursal->id == $dispositivo->cod_sucursal ? 'selected' : ''  }}>
                                            {{ $sucursal->nombre_sucursal }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_sucursal')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{--
                            <div class="form-group">
                                <label for="cod_sucursal">Sucursal</label>
                                <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonSucursal">
                                    @php
                                        // Establecer el nombre de la sucursal seleccionada por defecto
                                        $sucursalSeleccionada = $sucursales->firstWhere('id', $dispositivo->cod_sucursal);
                                        echo $sucursalSeleccionada ? $sucursalSeleccionada->nombre_sucursal : 'Seleccione una sucursal';
                                    @endphp
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuSucursal">
                                    <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                        <input type="text" class="form-control" id="searchSucursal" placeholder="Buscar sucursal..." autocomplete="off">
                                    </div>
                                    <div class="inner">
                                        @foreach ($sucursales as $sucursal)
                                            <a class="dropdown-item" data-value="{{ $sucursal->id }}" onclick="selectSucursal('{{ $sucursal->id }}', '{{ $sucursal->nombre_sucursal }}')">
                                                {{ $sucursal->nombre_sucursal }}
                                            </a>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="cod_sucursal" id="cod_sucursal" value="{{ old('cod_sucursal', $dispositivo->cod_sucursal) }}">
                                    @error('cod_sucursal')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
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
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>

                                <a href="{{ route('dispositivos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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
<script>
    // Función para seleccionar el cliente y actualizar el botón con el texto seleccionado
    function selectModel(clienteId, clienteName) {
        $("#dropdownButtonCliente").html(clienteName);
        $("#cod_cliente").val(clienteId);
        
        // Limpiar el dropdown de sucursales y restablecer el texto del botón
        $('#dropdownMenuSucursal .inner').empty();
        $("#cod_sucursal").val(''); // Limpiar el valor oculto de sucursal
        $("#dropdownButtonSucursal").html('Seleccione una Sucursal'); // Resetear el texto del botón
        $("#dropdownButtonSucursal").prop('disabled', true); // Deshabilitar el botón de sucursales

        // Filtrar sucursales según el cliente seleccionado
        filterSucursales(clienteId);
        $(".dropdown-menu").removeClass("show");
    }

    // Función para filtrar sucursales según el cliente
    function filterSucursales(clienteId) {
        $.ajax({
            url: '/dispositivos/getSucursales/' + clienteId, // Asegúrate de tener esta ruta configurada
            method: 'GET',
            success: function(data) {
                $('#dropdownMenuSucursal .inner').empty();
                data.forEach(function(sucursal) {
                    $('#dropdownMenuSucursal .inner').append(
                        `<a class="dropdown-item" data-value="${sucursal.id}" onclick="selectSucursal('${sucursal.id}', '${sucursal.nombre_sucursal}')">${sucursal.nombre_sucursal}</a>`
                    );
                });
                // Habilitar el botón de sucursales si hay sucursales disponibles
                if (data.length > 0) {
                    $("#dropdownButtonSucursal").prop('disabled', false);
                }
            }
        });
    }

    // Función para seleccionar la sucursal y actualizar el botón con el texto seleccionado
    function selectSucursal(sucursalId, sucursalName) {
        $("#dropdownButtonSucursal").html(sucursalName);
        $("#cod_sucursal").val(sucursalId);
        $(".dropdown-menu").removeClass("show");
    }

    // Filtrar las opciones mientras se escribe en el input de búsqueda para el cliente
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

    // Filtrar las opciones mientras se escribe en el input de búsqueda para la sucursal
    $("#searchSucursal").on("input", function () {
        var searchTerm = $(this).val().toLowerCase();
        $("#dropdownMenuSucursal .dropdown-item").each(function () {
            var sucursalName = $(this).text().toLowerCase();
            if (sucursalName.indexOf(searchTerm) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    // Evitar que el dropdown se cierre al hacer clic en el input de búsqueda del cliente
    $('#searchModelCliente').on('click', function (e) {
        e.stopPropagation();
    });

    // Cerrar el dropdown cuando se hace clic fuera de él
    $(document).on("click", function (e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });

    // Evitar que el dropdown se cierre al hacer clic en el input de búsqueda
    $('#searchSucursal').on('click', function (e) {
        e.stopPropagation();
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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