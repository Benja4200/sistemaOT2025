@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Editar Sucursal -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Sucursal</h2>
                    <div>
                        <!-- Botón Cancelar -->
                        @if($sucursal->cod_cliente)
                            <a href="{{ route('clientes.edit', $sucursal->cod_cliente) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                <i class="fas Example of arrow-left fa-arrow-left"></i> Ir al Cliente
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Formulario de Edición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Editar Información de la Sucursal
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito con SweetAlert2 -->
                        @if(session('success'))
                        <div id="success-message-edit" class="d-none">
                            <span id="success-type">{{ session('success_type', 'editar') }}</span>
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

                        <form action="{{ route('sucursales.update', ['sucursale' => $sucursal->id, 'from' => $from]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nombre_sucursal">Nombre</label>
                                <input type="text" name="nombre_sucursal" id="nombre_sucursal" class="form-control" value="{{ old('nombre_sucursal', $sucursal->nombre_sucursal) }}" required>
                                @error('nombre_sucursal')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="telefono_sucursal">Teléfono</label>
                                <input type="text" name="telefono_sucursal" id="telefono_sucursal" class="form-control" value="{{ old('telefono_sucursal', $sucursal->telefono_sucursal) }}" required>
                                @error('telefono_sucursal')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="direccion_sucursal">Dirección</label>
                                <input type="text" name="direccion_sucursal" id="direccion_sucursal" class="form-control" value="{{ old('direccion_sucursal', $sucursal->direccion_sucursal) }}" required>
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
                                            {{ (old('cliente_id') == $cliente->id || (isset($sucursal->cod_cliente) && $sucursal->cod_cliente == $cliente->id) ? 'selected' : '') }}>
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
                                    @php
                                        $clienteSeleccionado = $clientes->firstWhere('id', $sucursal->cod_cliente);
                                        echo $clienteSeleccionado ? $clienteSeleccionado->nombre_cliente : 'Seleccione un Cliente';
                                    @endphp
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
                                            <a class="dropdown-item" data-value="{{ $cliente->id }} "
                                                onclick="selectCliente('{{ $cliente->id }}', '{{ $cliente->nombre_cliente }}')">
                                                {{ $cliente->nombre_cliente }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <input type="hidden" name="cliente_id" id="cod_cliente" value="{{ old('cod_cliente', $sucursal->cod_cliente) }}">

                                </div>

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
                            <!-- Botón para agregar un nuevo contacto -->
                        

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
    
                                <a href="{{ route('sucursales.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                        
                        
                        <h3 class="mt-5">Contactos</h3>
                        
                        <div class="mt-4">
                            <a href="{{ route('contactos.create', ['from' => 'editar_sucursal','clienteId' => $sucursal->cod_cliente, 'sucursalId' => $sucursal->id]) }}" class="btn btn-success" style="background-color: #cc0066; border-color: #cc0066;">
                                <i class="fas fa-plus-circle"></i> Agregar Contacto
                            </a>
                        </div>
                        
                        @if($sucursal->contacto()->count() < 1)
                            <div class="alert alert-warning" role="alert">
                                Cliente sin sucursales.
                            </div>
                        @else
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Contacto</th>
                                        <th>Telefono</th>
                                        <th>Departamento</th>
                                        <th>Cargo</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sucursal->contacto as $contacto)
                                        <tr>
                                            <td> {{ $contacto->id }} </td>
                                            <td>{{ html_entity_decode($contacto->nombre_contacto) }}</td>
                                            <td>{{ html_entity_decode($contacto->telefono_contacto) }}</td>
                                            <td>{{ html_entity_decode($contacto->departamento_contacto) }}</td>
                                            <td>{{ html_entity_decode($contacto->cargo_contacto) }}</td>
                                            <td>{{ html_entity_decode($contacto->email_contacto) }}</td>
                                            <td>
                                                <a href="{{ route('contactos.edit', ['contacto' => $contacto->id, 'from' => 'editar_sucursal', 'sucursal_id' => $sucursal->id]) }}" class="btn btn-sm btn-custom-warning">
                                                    <i class="fas fa-edit"></i>
                                                    Editar
                                                </a>
                                                <form action="{{ route('destroy_contacto.metodo', $contacto->id) }}"  method="POST" class="delete-form" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-custom-danger" onclick="confirmDelete(this)">
                                                        <i class="fas fa-trash-alt"></i>
                                                        Eliminar
                                                    </button>
                                                </form>
                                            
                                            </td>
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
</main>

<!-- Incluye el archivo JavaScript -->
{{-- <script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    function confirmDelete(button) {
        const form = button.closest('form');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás recuperar esta sucursal después de eliminarla!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#cc6633', // Color del botón de confirmar
            cancelButtonColor: '#d33', // Color del botón de cancelar
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Envía el formulario si el usuario confirma
            }
        });

        // Forzar estilos de los botones después de que se muestre el modal
        Swal.getCancelButton().style.backgroundColor = '#d33'; // Color del botón de cancelar
        Swal.getConfirmButton().style.backgroundColor = '#cc6633'; // Color del botón de confirmar
    }
</script>
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
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('success') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
    
    @if(session('delete_error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('delete_error') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
    
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ $errors->first() }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection