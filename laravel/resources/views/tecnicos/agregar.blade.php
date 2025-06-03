@extends('layouts.master')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h2>Agregar Tecnico</h2>
                    </div>

                    <!-- Sección Tutorial -->
                    <div class="alert alert-info mt-4" role="alert">
                        <h5 class="alert-heading">Instrucciones</h5>
                        <p>Complete la siguiente información para agregar un técnico correctamente:</p>
                        <ul>
                            <li><strong>Nombre del Tecnico:</strong> Nombre del técnico.</li>
                            <li><strong>RUT:</strong> RUT del técnico.</li>
                            <li><strong>Teléfono:</strong> Número de teléfono del técnico.</li>
                            <li><strong>Email:</strong> Correo electrónico del técnico.</li>
                            <li><strong>Precio por Hora:</strong> Tarifa por hora del técnico.</li>
                        </ul>
                    </div>

                    <!-- Formulario de Adición -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Agregar Información del Técnico
                        </div>
                        <div class="card-body">

                            <!-- Mensaje de éxito con SweetAlert2 -->
                            @if (session('success'))
                                <div id="success-message" class="d-none">
                                    <span id="success-type">agregar</span>
                                    <span id="module-name">Técnico</span>
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

                            <form action="{{ route('tecnicos.store') }}" method="POST">
                                @csrf

                                <!-- Nombre del Técnico -->
                                <div class="form-group">
                                    <label for="nombre_tecnico">Nombre del Técnico</label>
                                    <input type="text" name="nombre_tecnico" id="nombre_tecnico" class="form-control"
                                        value="{{ old('nombre_tecnico') }}" required>
                                    @error('nombre_tecnico')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- RUT -->
                                <div class="form-group">
                                    <label for="rut_tecnico">RUT</label>
                                    <input type="text" name="rut_tecnico" id="rut_tecnico" class="form-control"
                                        value="{{ old('rut_tecnico') }}" required maxlength="12">
                                    @error('rut_tecnico')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Teléfono -->
                                <div class="form-group">
                                    <label for="telefono_tecnico">Teléfono</label>
                                    <input type="text" name="telefono_tecnico" id="telefono_tecnico" class="form-control"
                                        value="{{ old('telefono_tecnico') }}" required>
                                    @error('telefono_tecnico')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email_tecnico">Email</label>
                                    <input type="email" name="email_tecnico" id="email_tecnico" class="form-control"
                                        value="{{ old('email_tecnico') }}" required>
                                    @error('email_tecnico')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Precio por Hora -->
                                <div class="form-group">
                                    <label for="precio_hora_tecnico">Precio por Hora</label>
                                    <input type="number" step="0.01" name="precio_hora_tecnico" id="precio_hora_tecnico"
                                        class="form-control" value="{{ old('precio_hora_tecnico') }}" required>
                                    @error('precio_hora_tecnico')
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
                                    <label for="cod_usuario">Cliente Asociado</label>
                                    <select name="cod_usuario" id="cod_usuario" class="form-control select2" required style="width:100%">
                                        <option value="">Seleccione un cliente</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" 
                                                {{ (old('cod_usuario') == $usuario->id ? 'selected' : '') }}>
                                                {{ $usuario->nombre_usuario }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cliente_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{--
                                <div class="form-group">
                                    <label for="cod_usuario">Usuario</label>
                                
                                    <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonUsuario">
                                        Seleccione un Usuario
                                    </button>
                                
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuUsuario">
                                        <!-- input de búsqueda -->
                                        <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                            <input type="text" class="form-control" id="searchUsuario" placeholder="Buscar usuario..." autocomplete="off">
                                        </div>
                                        <!-- lista de opciones -->
                                        <div class="inner">
                                            @foreach ($usuarios as $usuario)
                                                <a class="dropdown-item" data-value="{{ $usuario->id }}" onclick="selectUsuario('{{ $usuario->id }}', '{{ $usuario->nombre_usuario }}')">
                                                    {{ $usuario->nombre_usuario }}
                                                </a>
                                            @endforeach
                                        </div>
                                
                                        <input type="hidden" name="cod_usuario" id="cod_usuario">
                                    </div>
                                
                                    @error('cod_usuario')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                --}}
                                {{--
                                <div class="form-group">
                                    <label for="cod_servicio">Servicios</label>
                                
                                    <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonServicios">
                                        Seleccione un Servicio
                                    </button>
                                
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuServicios">
                                        <!-- input de búsqueda -->
                                        <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                            <input type="text" class="form-control" id="searchServicios" placeholder="Buscar servicio..." autocomplete="off">
                                        </div>
                                        <!-- lista de opciones -->
                                        <div class="inner">
                                            @foreach($servicios as $servicio)
                                                <a class="dropdown-item" data-value="{{ $servicio->id }}" onclick="selectServicio('{{ $servicio->id }}', '{{ $servicio->nombre_servicio }}')">
                                                    {{ $servicio->nombre_servicio }}
                                                </a>
                                            @endforeach
                                        </div>
                                
                                        <input type="hidden" name="servicios[]" id="cod_servicio">
                                    </div>
                                
                                    @error('servicios')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <script>
                                    // Función para seleccionar un usuario
                                    function selectUsuario(usuarioId, usuarioName) {
                                        $("#dropdownButtonUsuario").html(usuarioName);
                                        $("#cod_usuario").val(usuarioId);
                                        $(".dropdown-menu").removeClass("show");
                                    }
                                
                                    // Función para seleccionar un servicio
                                    function selectServicio(servicioId, servicioName) {
                                        let selectedServicios = $("#cod_servicio").val();
                                        selectedServicios = selectedServicios ? selectedServicios.split(',') : [];
                                
                                        if (!selectedServicios.includes(servicioId)) {
                                            selectedServicios.push(servicioId);
                                        }
                                
                                        $("#cod_servicio").val(selectedServicios.join(','));
                                        $("#dropdownButtonServicios").html(servicioName);
                                        $(".dropdown-menu").removeClass("show");
                                    }
                                
                                    // Filtro de búsqueda para los usuarios
                                    $("#searchUsuario").on("input", function() {
                                        var searchTerm = $(this).val().toLowerCase();
                                
                                        $("#dropdownMenuUsuario .dropdown-item").each(function() {
                                            var usuarioName = $(this).text().toLowerCase();
                                
                                            if (usuarioName.indexOf(searchTerm) === -1) {
                                                $(this).hide();
                                            } else {
                                                $(this).show();
                                            }
                                        });
                                    });
                                
                                    // Filtro de búsqueda para los servicios
                                    $("#searchServicios").on("input", function() {
                                        var searchTerm = $(this).val().toLowerCase();
                                
                                        $("#dropdownMenuServicios .dropdown-item").each(function() {
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
                                
                                    $('#searchUsuario').on('click', function(e) {
                                        e.stopPropagation();
                                    });
                                
                                    $('#searchServicios').on('click', function(e) {
                                        e.stopPropagation();
                                    });
                                </script>
                                --}}
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <!-- Botón Guardar -->
                                    <button type="submit" class="btn btn-primary"
                                        style="background-color: #cc0066; border-color: #cc0066;">
                                        <i class="fas fa-save"></i> Guardar
                                    </button>

                                    <!-- Botón Cancelar -->
                                    <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary"
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
    <script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rutInput = document.getElementById('rut_tecnico');
    
            rutInput.addEventListener('input', function (e) {
                // Eliminar caracteres no numéricos
                let rut = this.value.replace(/[^0-9Kk]/g, '');  
                
                // Limitar a 12 caracteres
                if (rut.length > 12) {
                    rut = rut.substring(0, 12);
                }
                // Formatear el RUT
                if (rut.length > 1) {
                    rut = rut.replace(/^(\d{1,2})(\d{3})(\d{3})([Kk0-9])$/, '$1.$2.$3-$4');
                    rut = rut.replace(/^(\d{1})(\d{3})(\d{3})([Kk0-9])$/, '$1.$2.$3-$4');
                    rut = rut.replace(/^(\d{1,2})(\d{3})([Kk0-9])$/, '$1.$2-$3');
                    rut = rut.replace(/^(\d{1})([Kk0-9])$/, '$1-$2');
                }
    
                this.value = rut;
            });
        });
    
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Seleccione un usuario",
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
