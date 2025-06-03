@extends('layouts.master')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2>Editar Técnico</h2>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card mt-3">
            <div class="card-header">
                Editar Información del Técnico
            </div>
            <div class="card-body">
                <form action="{{ route('update_tecnico.metodo', $tecnico->id) }}" method="POST">
                    @csrf
                    @method('PUT')
        
                    <div class="mb-3">
                        <label for="nombre_tecnico" class="form-label">Nombre del Técnico</label>
                        <input type="text" class="form-control" id="nombre_tecnico" name="nombre_tecnico" value="{{ old('nombre_tecnico', $tecnico->nombre_tecnico) }}" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="rut_tecnico" class="form-label">RUT del Técnico</label>
                        <input type="text" class="form-control" id="rut_tecnico" name="rut_tecnico" value="{{ old('rut_tecnico', $tecnico->rut_tecnico) }}" required maxlength="12">
                    </div>
        
                    <div class="mb-3">
                        <label for="telefono_tecnico" class="form-label">Teléfono del Técnico</label>
                        <input type="text" class="form-control" id="telefono_tecnico" name="telefono_tecnico" value="{{ old('telefono_tecnico', $tecnico->telefono_tecnico) }}" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="email_tecnico" class="form-label">Email del Técnico</label>
                        <input type="email" class="form-control" id="email_tecnico" name="email_tecnico" value="{{ old('email_tecnico', $tecnico->email_tecnico) }}" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="precio_hora_tecnico" class="form-label">Precio por Hora</label>
                        <input type="number" class="form-control" id="precio_hora_tecnico" name="precio_hora_tecnico" value="{{ old('precio_hora_tecnico', $tecnico->precio_hora_tecnico) }}" required>
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
                                    {{ (old('cod_usuario') == $usuario->id || (isset($tecnico->cod_usuario) && $tecnico->cod_usuario == $usuario->id) ? 'selected' : '') }}>
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
        
                        <label for="usuario_id">seleccionar Usuario</label>
        
                        <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownButtonUsaroskix">
                            Seleccione un Usuario
                        </button>
        
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenuUsaroskix">
                            <!-- input de busqueda -->
                            <div class="bs-searchbox" style="padding-left: 10px; padding-right: 10px;">
                                <input type="text" class="form-control" id="searchUsaroskix" placeholder="Buscar usuario..." autocomplete="off">
                            </div>
                                                
                            <!-- lista de opciones -->
                            <div class="inner">
                                @foreach ($usuariosx as $usuario)
                                    <a class="dropdown-item" data-value="{{ $usuario->id }}" onclick="selectUsaroskix('{{ $usuario->id }}', '{{ $usuario->nombre_usuario }}')">
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
                    <script>
                        // Función para seleccionar el modelo y actualizar el botón con el texto seleccionado
                        function selectUsaroskix(usuarioId, usuarioName) {
                            
                            $("#dropdownButtonUsaroskix").html(usuarioName);
                            
                            $("#cod_usuario").val(usuarioId);
            
                            // Cerramos el dropdown
                            $(".dropdown-menu").removeClass("show");
                        }
            
                        // Filtrar las opciones mientras escribes en el input de búsqueda para el modelo
                        $("#searchUsaroskix").on("input", function () {
                            var searchTerm = $(this).val().toLowerCase();
            
                            // Iterar sobre todas las opciones del dropdown de modelos
                            $("#dropdownMenuUsaroskix .dropdown-item").each(function () {
                                var usuarioName = $(this).text().toLowerCase();
            
                                // Si el nombre del modelo contiene el texto de búsqueda, mostramos la opción
                                if (usuarioName.indexOf(searchTerm) === -1) {
                                    $(this).hide();  // Ocultar si no coincide con la búsqueda
                                } else {
                                    $(this).show();  // Mostrar si coincide
                                }
                            });
                        });
            
                        // Filtrar las opciones mientras escribes en el input de búsqueda para la sucursal
                        $("#searchModelSucursal").on("input", function () {
                            var searchTerm = $(this).val().toLowerCase();
            
                            // Iterar sobre todas las opciones del dropdown de sucursales
                            $("#dropdownMenuSucursal .dropdown-item").each(function () {
                                var sucursalName = $(this).text().toLowerCase();
            
                                // Si el nombre de la sucursal contiene el texto de búsqueda, mostramos la opción
                                if (sucursalName.indexOf(searchTerm) === -1) {
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
                        $('#searchUsaroskix').on('click', function (e) {
                            e.stopPropagation();  // Previene el cierre del dropdown
                        });
                    </script>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-success" style="background-color: #cc0066; border-color: #cc0066;">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                            <i class="fas fa-times-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
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
