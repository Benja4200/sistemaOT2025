@extends('layouts.master')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Editar Contacto -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Contacto</h2>
                    
                    @if($contacto->cod_sucursal)
                        <a href="{{ route('sucursales.edit',$contacto->cod_sucursal) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                            <i class="fas Example of arrow-left fa-arrow-left"></i> Ir a la Sucursal
                        </a>
                    @endif
                </div>
                
                
                <!-- Formulario de Edición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Editar Información del Contacto
                    </div>
                    <div class="card-body">
                        <form action="{{ route('contactos.update', ['contacto' => $contacto->id, 'from' => $from]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nombre_contacto">Nombre</label>
                                <input type="text" name="nombre_contacto" id="nombre_contacto" class="form-control @error('nombre_contacto') is-invalid @enderror" value="{{ old('nombre_contacto', $contacto->nombre_contacto) }}" required>
                                @error('nombre_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="telefono_contacto">Teléfono</label>
                                <input type="text" name="telefono_contacto" id="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" value="{{ old('telefono_contacto', $contacto->telefono_contacto) }}" required>
                                @error('telefono_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="departamento_contacto">Departamento</label>
                                <input type="text" name="departamento_contacto" id="departamento_contacto" class="form-control @error('departamento_contacto') is-invalid @enderror" value="{{ old('departamento_contacto', $contacto->departamento_contacto) }}">
                                @error('departamento_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="cargo_contacto">Cargo</label>
                                <input type="text" name="cargo_contacto" id="cargo_contacto" class="form-control @error('cargo_contacto') is-invalid @enderror" value="{{ old('cargo_contacto', $contacto->cargo_contacto) }}">
                                @error('cargo_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email_contacto">Correo Electrónico</label>
                                <input type="email" name="email_contacto" id="email_contacto" class="form-control @error('email_contacto') is-invalid @enderror" value="{{ old('email_contacto', $contacto->email_contacto) }}" required>
                                @error('email_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
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

                            <!-- Selector de Clientes -->
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
                            <div class="form-group">
                                <label for="cod_cliente">Cliente</label>
                                <select name="cod_cliente" id="cod_cliente" class="form-control select2" required style="width:100%">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                            {{ (old('cod_cliente') == $cliente->id || (isset($contacto->sucursal->cod_cliente) && $contacto->sucursal->cod_cliente == $cliente->id) ? 'selected' : '') }}>
                                            {{ $cliente->nombre_cliente }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_cliente')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Selector de Sucursales -->
                            {{--
                            <div class="form-group">
                                <label for="cod_sucursal">Sucursal</label>
                                <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonSucursal">
                                    @php
                                        // Establecer el nombre de la sucursal seleccionada por defecto
                                        $sucursalSeleccionada = $sucursales->firstWhere('id', $contacto->cod_sucursal);
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
                                    <input type="hidden" name="cod_sucursal" id="cod_sucursal" value="{{ old('cod_sucursal', $contacto->cod_sucursal) }}">
                                    @error('cod_sucursal')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            --}}
                            <div class="form-group">
                                <label for="cod_sucursal">Sucursal</label>
                                <select name="cod_sucursal" id="cod_sucursal" class="form-control select2" {{ $contacto->sucursal->cod_cliente ? '' : ' disabled' }}  required style="width:100%">
                                    <option value="">Seleccione una Sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}" 
                                            {{ (old('cod_sucursal') == $sucursal->id || (isset($contacto->cod_sucursal) && $contacto->cod_sucursal == $sucursal->id) ? 'selected' : '') }}>
                                            {{ $sucursal->nombre_sucursal }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
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

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                                @php
                                    // Determinar la URL de cancelación
                                    $cancelUrl = isset($from) && $from === 'editar_sucursal' && $contacto->cod_sucursal ? route('sucursales.edit', $contacto->cod_sucursal) : route('contactos.index');
                                @endphp
                                <a href="{{ route('contactos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Seleccione una opción...",
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
    });
</script>
@endsection