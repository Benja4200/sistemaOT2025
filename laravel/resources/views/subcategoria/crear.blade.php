@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Agregar Subcategoría -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Agregar Subcategoría</h2>
                </div>

                <!-- Sección Tutorial -->
               {{-- <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading"><strong>Tutorial</strong></h5>
                    <p>Siga las siguientes indicaciones para agregar una subcategoría correctamente:</p>
                    <ul>
                        <li><strong>Nombre de la Subcategoría:</strong> Nombre de la nueva subcategoría.</li>
                        <li><strong>Categoría:</strong> Seleccione la categoría a la que pertenece esta subcategoría.</li>
                    </ul>
                </div> --}}

                <!-- Formulario de Adición -->
                <div class="card mt-3">

                    <div class="card-body">

                        <!-- Mensaje de éxito -->
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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

                        <form action="{{ route('subcategoria.store') }}" method="POST">
                            @csrf

                            <!-- Nombre de la Subcategoría -->
                            <div class="form-group">
                                <label for="nombre_subcategoria">Nombre de la Subcategoría</label>
                                <input type="text" name="nombre_subcategoria" id="nombre_subcategoria" class="form-control" value="{{ old('nombre_subcategoria') }}" required>
                                {{-- 
                                @error('nombre_subcategoria')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                                --}}
                            </div>
                            
                            <!-- Seleccionar Categoría -->
                            @if($categoria_id) <!-- Si existe el ID de la categoría -->
                                <input type="hidden" name="cod_categoria" value="{{ $categoria_id }}">
                                {{-- <div class="alert alert-info">Categoría seleccionada: {{ $categorias->firstWhere('id', $categoria_id)->nombre_categoria }}</div> --}}
                            @else
                                <div class="form-group">
                                    <label for="cod_categoria">Categoría</label>
                                    <select name="cod_categoria" id="cod_categoria" class="form-control" required>
                                        <option value="">Seleccione una Categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ old('cod_categoria') == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->nombre_categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cod_categoria')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar
                                </button>

                                <!-- Botón Cancelar -->
                                <a href="{{ route('categoria.edit',$categoria_id) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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
@endsection