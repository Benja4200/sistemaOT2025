@extends('layouts.master')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Agregar Sucursal -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h2>Agregar Sucursal</h2>
                        @if($clienteId)
                            <a href="{{ route('clientes.edit', $clienteId) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                <i class="fas Example of arrow-left fa-arrow-left"></i> Volver al Cliente
                            </a>
                        @endif
                    </div>

                    <!-- Sección Tutorial -->
                    <div class="alert alert-info mt-4" role="alert">
                        <h5 class="alert-heading">Tutorial</h5>
                        <p>Agregue la siguiente información para agregar una sucursal correctamente:</p>
                        <ul>
                            <li><strong>Nombre de la Sucursal:</strong> Nombre de la sucursal.</li>
                            <li><strong>Teléfono:</strong> Número de teléfono de la sucursal.</li>
                            <li><strong>Dirección:</strong> Dirección física de la sucursal.</li>
                            <li><strong>Cliente:</strong> Cliente asociado con la sucursal.</li>
                        </ul>
                    </div>

                    <!-- Formulario de Adición -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Agregar Información de la Sucursal
                        </div>
                        <div class="card-body">

                            <!-- Mensaje de éxito con SweetAlert2 -->
                            @if(session('success'))
                                <div id="success-message" class="d-none">
                                    <span id="success-type">agregar</span>
                                    <span id="module-name">Sucursal</span>
                                    <span id="redirect-url">{{ route('sucursales.index') }}</span>
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

                            <form action="{{ route('sucursales.store', ['from' => request('from')]) }}" method="POST">
                                @csrf

                                <!-- Información de la Sucursal -->
                                <div class="form-group">
                                    <label for="nombre_sucursal">Nombre de la Sucursal</label>
                                    <input type="text" name="nombre_sucursal" id="nombre_sucursal" class="form-control"
                                        value="{{ old('nombre_sucursal') }}" required>
                                    @error('nombre_sucursal')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="telefono_sucursal">Teléfono</label>
                                    <input type="text" name="telefono_sucursal" id="telefono_sucursal" class="form-control"
                                        value="{{ old('telefono_sucursal') }}" required>
                                    @error('telefono_sucursal')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="direccion_sucursal">Dirección</label>
                                    <input type="text" name="direccion_sucursal" id="direccion_sucursal"
                                        class="form-control" value="{{ old('direccion_sucursal') }}" required>
                                    @error('direccion_sucursal')
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
                                    <label for="cliente_id">Cliente Asociado</label>
                                    <select name="cliente_id" id="cliente_id" class="form-control select2" required style="width:100%">
                                        <option value="">Seleccione un cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" 
                                                {{ (old('cliente_id') == $cliente->id || (isset($clienteId) && $clienteId == $cliente->id) ? 'selected' : '') }}>
                                                {{ $cliente->nombre_cliente }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cliente_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- 
                                <div class="form-group">
                                    <label for="cliente_id">Cliente Asociado</label>

                                    <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" id="dropdownButtonCliente">
                                            {{ 
                                                ($clientes->firstWhere('id', old('cliente_id', request('cliente_id')))->nombre_cliente ?? 'Seleccione un cliente') 
                                            }}
                                        </button>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        id="dropdownMenuCliente">
                                        <!-- input de búsqueda -->
                                        <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                            <input type="text" class="form-control" id="searchCliente"
                                                placeholder="Buscar cliente..." autocomplete="off">
                                        </div>
                                        <!-- lista de opciones -->
                                        <div class="inner">
                                            @foreach($clientes as $cliente)
                                                <a class="dropdown-item" data-value="{{ $cliente->id }}"
                                                    onclick="selectCliente('{{ $cliente->id }}', '{{ $cliente->nombre_cliente }}')">
                                                    {{ $cliente->nombre_cliente }}
                                                </a>
                                            @endforeach
                                        </div>

                                       
                                    </div>
                                    <input type="hidden" name="cliente_id" id="cod_cliente" value="{{ old('cliente_id', request('cliente_id')) }}">   
                                    @error('cliente_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>



                                <script>
                                    // Función para seleccionar el cliente y actualizar el botón con el texto seleccionado
                                    function selectCliente(clienteId, clienteName) {
                                        // Actualizamos el texto del botón para el cliente
                                        $("#dropdownButtonCliente").html(clienteName);
                                        // Colocamos el valor en el campo oculto de cliente
                                        $("#cod_cliente").val(clienteId);

                                        // Cerramos el dropdown
                                        $(".dropdown-menu").removeClass("show");
                                    }

                                    // Filtrar las opciones mientras escribes en el input de búsqueda para el cliente
                                    $("#searchCliente").on("input", function () {
                                        var searchTerm = $(this).val().toLowerCase();

                                        // Iterar sobre todas las opciones del dropdown de clientes
                                        $("#dropdownMenuCliente .dropdown-item").each(function () {
                                            var clienteName = $(this).text().toLowerCase();

                                            // Si el nombre del cliente contiene el texto de búsqueda, mostramos la opción
                                            if (clienteName.indexOf(searchTerm) === -1) {
                                                $(this).hide();  // Ocultar si no coincide con la búsqueda
                                            } else {
                                                $(this).show();  // Mostrar si coincide
                                            }
                                        });
                                    });

                                    // Asegurarse de que al hacer clic fuera del dropdown se cierre correctamente
                                    $(document).on("click", function (e) {
                                        if (!$(e.target).closest('.dropdown').length) {
                                            $('.dropdown-menu').removeClass('show');
                                        }
                                    });

                                    // Evitar que el dropdown se cierre al hacer clic en el input de búsqueda
                                    $('#searchCliente').on('click', function (e) {
                                        e.stopPropagation();  // Previene el cierre del dropdown
                                    });
                                </script>
                                --}}
                                 <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const clienteId = '{{ old('cliente_id', request('cliente_id')) }}';
                                        
                                        // Solo si existe un cliente seleccionado en los datos previos o en la URL
                                        if (clienteId) {
                                            const cliente = document.querySelector(`#dropdownMenuCliente a[data-value="${clienteId}"]`);
                                            
                                            if (cliente) {
                                                const nombreCliente = cliente.textContent.trim();
                                                document.getElementById('dropdownButtonCliente').innerHTML = nombreCliente;
                                                document.getElementById('cod_cliente').value = clienteId;
                                            }
                                        }
                                    });
                                </script>
                            
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <!-- Botón Guardar -->
                                    <button type="submit" class="btn btn-primary"
                                        style="background-color: #cc0066; border-color: #cc0066;">
                                        <i class="fas fa-save"></i> Guardar
                                    </button>
                                    @php
                                        // Determinar la URL de cancelación
                                        $cancelUrl = isset($from) && $from === 'editar_cliente' && $clienteId ? route('clientes.edit', $clienteId) : route('sucursales.index');
                                    @endphp
                                    
                                    <!-- Botón Cancelar -->
                                    <a href="{{ route('sucursales.index') }}" class="btn btn-secondary"
                                        style="background-color: #cc0066; border-color: #cc0066;">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Seleccione un cliente",
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