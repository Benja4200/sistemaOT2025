@extends('layouts.master')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Agregar Contacto</h2>
                    @if($sucursalId)
                        <a href="{{ route('sucursales.edit', $sucursalId) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                            <i class="fas Example of arrow-left fa-arrow-left"></i> Volver a la Sucursal
                        </a>
                    @endif
                </div>

                <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading">Tutorial</h5>
                    <p>Agregue la siguiente información para agregar un contacto correctamente:</p>
                    <ul>
                        <li><strong>Nombre del Contacto:</strong> Nombre completo del contacto.</li>
                        <li><strong>Teléfono:</strong> Número de teléfono del contacto.</li>
                        <li><strong>Departamento:</strong> Departamento en el que trabaja el contacto.</li>
                        <li><strong>Cargo:</strong> Cargo del contacto en la empresa.</li>
                        <li><strong>Correo Electrónico:</strong> Correo electrónico del contacto.</li>
                        <li><strong>Sucursal:</strong> Sucursal a la que pertenece el contacto.</li>
                    </ul>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        Agregar Información del Contacto
                    </div>
                    <div class="card-body">

                        @if(session('success'))
                            <div id="success-message" class="d-none">
                                <span id="success-type">{{ session('success_type', 'agregar') }}</span>
                                <span id="module-name">Contacto</span>
                                <span id="redirect-url">{{ route('contactos.index') }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contactos.store', ['from' => $from] ) }}" method="POST">
                            @csrf

                            <style>
                                .form-group { margin-bottom: 20px; }
                                .dropdown-menu { max-height: 300px; overflow-y: auto; }
                            </style>
                            
                            {{-- Cliente --}}
                            <div class="form-group">
                                <label for="cod_cliente">Cliente</label>
                                <select name="cod_cliente" id="cod_cliente" class="form-control select2" required style="width:100%">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                            {{ (old('cod_cliente') == $cliente->id || (isset($clienteSeleccionado) && $clienteSeleccionado->id == $cliente->id) ? 'selected' : '') }}>
                                            {{ $cliente->nombre_cliente }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_cliente')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Sucursal --}}
                            <div class="form-group">
                                <label for="cod_sucursal">Sucursal</label>
                                <select name="cod_sucursal" id="cod_sucursal" class="form-control select2" {{ $clienteSeleccionado ? '' : ' disabled' }}  required style="width:100%">
                                    <option value="">Seleccione una Sucursal</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}" 
                                            {{ (old('cod_sucursal') == $sucursal->id || (isset($sucursalSeleccionada) && $sucursalSeleccionada->id == $sucursal->id) ? 'selected' : '') }}>
                                            {{ $sucursal->nombre_sucursal }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{--
                            <div class="form-group">
                                <label for="cod_sucursal">Sucursal</label>
                                <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" id="dropdownButtonSucursal" >
                                            Seleccione una Sucursal
                            </button>
                                <div class="dropdown-menu" id="dropdownMenuSucursal">
                                    <div class="bs-searchbox p-2">
                                        <input type="text" class="form-control" id="searchSucursal" placeholder="Buscar sucursal...">
                                    </div>
                                    <div class="inner"></div>
                                </div>
                                <input type="hidden" name="cod_sucursal" id="cod_sucursal" value="{{ $sucursalSeleccionada?->id }}">
                                @error('cod_sucursal')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            --}}
                            {{-- Campos de contacto --}}
                            <div class="form-group">
                                <label for="nombre_contacto">Nombre del Contacto</label>
                                <input type="text" name="nombre_contacto" class="form-control" value="{{ old('nombre_contacto') }}" required>
                                @error('nombre_contacto') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="telefono_contacto">Teléfono</label>
                                <input type="text" name="telefono_contacto" class="form-control" value="{{ old('telefono_contacto') }}" required>
                                @error('telefono_contacto') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="departamento_contacto">Departamento</label>
                                <input type="text" name="departamento_contacto" class="form-control" value="{{ old('departamento_contacto') }}">
                                @error('departamento_contacto') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="cargo_contacto">Cargo</label>
                                <input type="text" name="cargo_contacto" class="form-control" value="{{ old('cargo_contacto') }}">
                                @error('cargo_contacto') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="email_contacto">Correo Electrónico</label>
                                <input type="email" name="email_contacto" class="form-control" value="{{ old('email_contacto') }}" required>
                                @error('email_contacto') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
                                </button>
                                
                                @php
                                    // Determinar la URL de cancelación
                                    $cancelUrl = isset($from) && $from === 'editar_sucursal' && $sucursalId ? route('sucursales.edit', $sucursalId) : route('contactos.index');
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
{{--
<script>
    function selectModel(clienteId, clienteName, type) {
        if (type === 'Cliente') {
            $("#dropdownButtonCliente").html(clienteName);
            $("#cod_cliente").val(clienteId);
            $('#dropdownMenuSucursal .inner').empty();
            $("#cod_sucursal").val('');
            $("#dropdownButtonSucursal").html('Seleccione una Sucursal');
            $("#dropdownButtonSucursal").prop('disabled', true);
            getSucursales(clienteId);
        }
        $(".dropdown-menu").removeClass("show");
    }

    function getSucursales(clienteId) {
        $.ajax({
            url: '/dispositivos/getSucursales/' + clienteId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#dropdownMenuSucursal .inner').empty();
                $.each(data, function(index, sucursal) {
                    $('#dropdownMenuSucursal .inner').append(
                        '<a class="dropdown-item" data-value="' + sucursal.id + '" onclick="selectSucursal(\'' + sucursal.id + '\', \'' + sucursal.nombre_sucursal + '\')">' + sucursal.nombre_sucursal + '</a>'
                    );
                });
                $("#dropdownButtonSucursal").prop('disabled', false);
            },
            error: function(xhr) {
                console.error("Error al obtener las sucursales:", xhr);
            }
        });
    }

    function selectSucursal(sucursalId, sucursalName) {
        $("#dropdownButtonSucursal").html(sucursalName);
        $("#cod_sucursal").val(sucursalId);
        $(".dropdown-menu").removeClass("show");
    }

    $("#searchModelCliente").on("input", function() {
        var searchTerm = $(this).val().toLowerCase();
        $("#dropdownMenuCliente .dropdown-item").each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) !== -1);
        });
    });

    $("#searchSucursal").on("input", function() {
        var searchTerm = $(this).val().toLowerCase();
        $("#dropdownMenuSucursal .dropdown-item").each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) !== -1);
        });
    });

    $(document).on("click", function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });

    $('#searchModelCliente, #searchSucursal').on('click', function(e) {
        e.stopPropagation();
    });

    // Preselección al cargar
    $(document).ready(function () {
        @if(isset($clienteSeleccionado))
            selectModel('{{ $clienteSeleccionado->id }}', '{{ $clienteSeleccionado->nombre_cliente }}', 'Cliente');
        @endif

        @if(isset($sucursalSeleccionada))
            setTimeout(function () {
                selectSucursal('{{ $sucursalSeleccionada->id }}', '{{ $sucursalSeleccionada->nombre_sucursal }}');
            }, 800);
        @endif
    });
</script>
<script>
    // Función para obtener el valor de un parámetro en la URL
    function getUrlParameter(name) {
        var url = window.location.href;
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
        if (results == null) {
            return null;
        } else {
            return decodeURIComponent(results[1]) || 0;
        }
    }

    $(document).ready(function () {
        // Obtener los parámetros de la URL
        var clienteId = getUrlParameter('clienteId');
        var sucursalId = getUrlParameter('sucursalId');

        // Si hay un clienteId en la URL, ocultar el dropdown y seleccionar el cliente
        if (clienteId) {
            $("#dropdownMenuCliente").hide(); // Ocultar el dropdown de cliente
            $("#dropdownButtonCliente").html("Cliente Seleccionado"); // Cambiar el texto del botón
            $("#cod_cliente").val(clienteId); // Establecer el valor del cliente en el input hidden

            // Aquí puedes hacer una petición AJAX si necesitas obtener el nombre del cliente
            // o alguna información adicional.
            // selectModel(clienteId, 'Nombre del Cliente', 'Cliente'); // Si ya tienes el nombre del cliente.
        }

        // Si hay un sucursalId en la URL, se puede hacer algo similar si es necesario.
        if (sucursalId) {
            $("#dropdownButtonSucursal").prop('disabled', true); // Desactivar el dropdown de sucursal
            $("#cod_sucursal").val(sucursalId); // Establecer el valor del sucursalId
            // Aquí puedes hacer una petición AJAX para preseleccionar la sucursal si es necesario.
        }

        // Lógica para el dropdown de Cliente
        if (clienteId) {
            // Si hay clienteId, ya está preseleccionado, no mostrar el dropdown
            selectModel(clienteId, "Cliente Seleccionado", 'Cliente');
        }
    });
</script>
--}}
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
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