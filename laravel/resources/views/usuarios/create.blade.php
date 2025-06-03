@extends('layouts.master')

@section('content')

<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Crear Técnico para el Usuario: {{ $usuario->nombre_usuario }}</h2>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <!-- Mostrar mensajes de error si los hay -->
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

                    <!-- Campo Nombre del Técnico -->
                    <div class="mb-3">
                        <label for="nombre_tecnico" class="form-label">Nombre del Técnico</label>
                        <input type="text" class="form-control" id="nombre_tecnico" name="nombre_tecnico" value="{{ old('nombre_tecnico') }}" required>
                    </div>

                    <!-- Campo RUT del Técnico -->
                    <div class="mb-3">
                        <label for="rut_tecnico" class="form-label">RUT del Técnico</label>
                        <input type="text" class="form-control" id="rut_tecnico" name="rut_tecnico" value="{{ old('rut_tecnico') }}" required>
                    </div>

                    <!-- Campo Teléfono del Técnico -->
                    <div class="mb-3">
                        <label for="telefono_tecnico" class="form-label">Teléfono del Técnico</label>
                        <input type="text" class="form-control" id="telefono_tecnico" name="telefono_tecnico" value="{{ old('telefono_tecnico') }}" required>
                    </div>

                    <!-- Campo Email del Técnico -->
                    <div class="mb-3">
                        <label for="email_tecnico" class="form-label">Email del Técnico</label>
                        <input type="email" class="form-control" id="email_tecnico" name="email_tecnico" value="{{ old('email_tecnico') }}" required>
                    </div>

                    <!-- Campo Precio por Hora del Técnico -->
                    <div class="mb-3">
                        <label for="precio_hora_tecnico" class="form-label">Precio por Hora</label>
                        <input type="number" class="form-control" id="precio_hora_tecnico" name="precio_hora_tecnico" value="{{ old('precio_hora_tecnico') }}" required>
                    </div>

                    <!-- Campo Usuario Asociado (Oculto) -->
                    <input type="hidden" name="cod_usuario" value="{{ $usuario->id }}">

                    <!-- Botón de Crear Técnico -->
                    <button type="submit" class="btn btn-primary" style="background-color: #cc6633; border-color: #cc6633;">Crear Técnico</button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
