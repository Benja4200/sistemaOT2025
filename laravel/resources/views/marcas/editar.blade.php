@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Editar Marca -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Marca</h2>
                </div>

                <!-- Sección Tutorial -->
                <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading">Tutorial</h5>
                    <p>Modifique la siguiente información de la marca:</p>
                    <ul>
                        <li><strong>Nombre de la Marca:</strong> Asegúrese de que sea único y representativo.</li>
                    </ul>
                </div>

                <!-- Formulario de Edición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Editar Información de la Marca
                    </div>
                    <div class="card-body">

                        <!-- Mensajes de error -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('marcas.update', $marca->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nombre de la Marca -->
                            <div class="form-group">
                                <label for="nombre_marca">Nombre de la Marca</label>
                                <input type="text" name="nombre_marca" id="nombre_marca" class="form-control" value="{{ old('nombre_marca', $marca->nombre_marca) }}" required>
                                @error('nombre_marca')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Actualizar
                                </button>

                                <a href="{{ route('marcas.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
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
