@extends('layouts.master')

@section('content')

<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <h3>Home / Roles / Crear</h3>
    <br>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Crear Rol</h2>
        </div>

        <div class="card mt-3">
            <div class="card-body">

                <!-- Mensaje de éxito con SweetAlert2 -->
                @if(session('success'))
                <div id="success-message" class="d-none">
                    <span id="success-type">{{ session('success_type', 'agregar') }}</span>
                    <span id="module-name">Rol</span>
                    <span id="redirect-url">{{ route('roles.index') }}</span>
                </div>
                @endif

                <!-- Manejo de errores -->
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}">
                    </div>

                    <div class="form-group">
                        <label for="color">Color:</label>
                        <input type="color" id="color" name="color" value="{{ old('color') }}">
                    </div>

                    <div class="mb-3">
                        <label for="permissions" class="form-label">Permisos</label>
                        <div class="form-check">
                            @foreach($permissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                    {{ $permission->description }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="background-color: #cc6633; border-color: #cc6633;">Crear Rol</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Incluye el archivo JavaScript para manejar mensajes de éxito y confirmación de eliminación -->
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>

@endsection
