@extends('layouts.master')

@section('content')

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Agregar Sublínea -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h2>Agregar Sublínea</h2>
                    </div>

                    <!-- Sección Tutorial -->
                    {{-- <div class="alert alert-info mt-4" role="alert">
                        <h5 class="alert-heading"><strong>Tutorial</strong></h5>
                        <p>Siga las siguientes indicaciones para agregar una sublínea correctamente:</p>
                        <ul>
                            <li><strong>Nombre de la Sublínea:</strong> Nombre de la nueva sublínea.</li>
                            <li><strong>Línea:</strong> Seleccione la línea a la que pertenece esta sublínea.</li>
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

                            <form action="{{ route('sublineas.store') }}" method="POST">
                                @csrf

                                <!-- Nombre de la Sublínea -->
                                <div class="form-group">
                                    <label for="nombre_sublinea">Nombre de la Sublínea</label>
                                    <input type="text" name="nombre_sublinea" id="nombre_sublinea" class="form-control" value="{{ old('nombre_sublinea') }}" required>
                                    
                                    {{--
                                    @error('nombre_sublinea')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                                
                                
                                @if($linea_id) <!-- Si existe el ID de la categoría -->
                                    <input type="hidden" name="cod_linea" value="{{ $linea_id }}">
                                    {{-- <div class="alert alert-info">Linea seleccionada: {{ $lineas->firstWhere('id', $linea_id)->nombre_linea }}</div> --}}
                                @else
                                <!-- Seleccionar Línea -->
                                    <div class="form-group">
                                        <label for="cod_linea">Línea</label>
                                        <select name="cod_linea" id="cod_linea" class="form-control" required>
                                            <option value="">Seleccione una Línea</option>
                                            @foreach($lineas as $linea)
                                                <option value="{{ $linea->id }}" {{ old('cod_linea') == $linea->id ? 'selected' : '' }}>
                                                    {{ $linea->nombre_linea }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cod_linea')
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
                                    <a href="{{ route('lineas.edit',$linea_id) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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

@endsection
