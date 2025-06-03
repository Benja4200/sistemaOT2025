@extends('layouts.master')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Agregar Modelo -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Agregar Modelo</h2>
                </div>

                <!-- Sección Tutorial -->
                <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading">Tutorial</h5>
                    <p>Agregue la siguiente información para agregar un modelo correctamente:</p>
                    <ul>
                        <li><strong>Categoría:</strong> Seleccione la categoría a la que pertenece el modelo.</li>
                        <li><strong>Subcategoría:</strong> Seleccione la subcategoría del modelo.</li>
                        <li><strong>Línea:</strong> Seleccione la línea del modelo.</li>
                        <li><strong>Sublínea:</strong> Seleccione la sublínea del modelo.</li>
                        <li><strong>Nombre del Modelo:</strong> Nombre completo del modelo.</li>
                        <li><strong>Número de Parte:</strong> Número de parte del modelo, si está disponible.</li>
                        <li><strong>Descripción Corta:</strong> Descripción breve del modelo.</li>
                        <li><strong>Descripción Larga:</strong> Descripción detallada del modelo.</li>
                        <li><strong>Marca:</strong> Seleccione la marca del modelo.</li>
                    </ul>
                </div>

                <!-- Formulario de Adición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Agregar Información del Modelo
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito -->
                        @if(session('success'))
                        <div id="success-message" class="d-none">
                            <span id="success-type">{{ session('success_type', 'agregar') }}</span>
                            <span id="module-name">Modelo</span>
                            <span id="redirect-url">{{ route('modelos.index') }}</span>
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

                        <form action="{{ route('modelos.store') }}" method="POST">
                            @csrf
                            
                
                              
                            <!-- Sublinea con filtro -->
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
                            
                            <div class="form-group">
                                <label for="cod_subcategoria">Subcategoría</label>
                                <select name="cod_subcategoria" id="cod_subcategoria" class="form-control select2" disabled style="width:100%">
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
                                        <option value="{{ $sublinea->id }}">
                                            {{ $sublinea->nombre_sublinea }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cod_sublinea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información del Modelo -->
                            <div class="form-group">
                                <label for="nombre_modelo">Nombre del Modelo</label>
                                <input type="text" name="nombre_modelo" id="nombre_modelo" class="form-control" value="{{ old('nombre_modelo') }}" required>
                                @error('nombre_modelo')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="part_number_modelo">Número de Parte</label>
                                <input type="text" name="part_number_modelo" id="part_number_modelo" class="form-control" value="{{ old('part_number_modelo') }}">
                                @error('part_number_modelo')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="desc_corta_modelo">Descripción Corta</label>
                                <textarea name="desc_corta_modelo" id="desc_corta_modelo" class="form-control">{{ old('desc_corta_modelo') }}</textarea>
                                @error('desc_corta_modelo')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="desc_larga_modelo">Descripción Larga</label>
                                <textarea name="desc_larga_modelo" id="desc_larga_modelo" class="form-control">{{ old('desc_larga_modelo') }}</textarea>
                                @error('desc_larga_modelo')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Selección de Marca -->
                            <div class="form-group">
                                <label for="cod_marca">Marca</label>
                                <select name="cod_marca" id="cod_marca" class="form-control select2" required>
                                    <option value="">Seleccionar Marca</option>
                                    @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}" {{ old('cod_marca') == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nombre_marca }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('cod_marca')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
                                </button>

                                <!-- Botón Cancelar -->
                                <a href="{{ route('modelos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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
<script src="{{ asset('assets/js/modelos/filtromodelos.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('.select2').select2({
            placeholder: "Seleccione una opción...",
            allowClear: true
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
@endsection
