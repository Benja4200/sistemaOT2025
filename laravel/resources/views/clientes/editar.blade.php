@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Editar Cliente -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Cliente</h2>
                </div>

                <!-- Formulario de Edición -->
                <div class="card mt-3">
                    <div class="card-header">
                        Editar Información del Cliente
                    </div>
                    <div class="card-body">

                        <!-- Mensaje de éxito con SweetAlert2 -->
                        @if(session('success'))
                        <div id="success-message-edit" class="d-none">
                            <span id="success-type">{{ session('success_type', 'editar') }}</span>
                            <span id="module-name">Cliente</span>
                            <span id="redirect-url">{{ route('clientes.index') }}</span>
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

                        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nombre_cliente">Nombre</label>
                                <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" value="{{ old('nombre_cliente', $cliente->nombre_cliente) }}" required>
                                @error('nombre_cliente')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="rut_cliente">RUT</label>
                                <input type="text" name="rut_cliente" id="rut_cliente" class="form-control" value="{{ old('rut_cliente', $cliente->rut_cliente) }}" required>
                                @error('rut_cliente')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email_cliente">Correo</label>
                                <input type="email" name="email_cliente" id="email_cliente" class="form-control" value="{{ old('email_cliente', $cliente->email_cliente) }}" required>
                                @error('email_cliente')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="telefono_cliente">Teléfono</label>
                                <input type="text" name="telefono_cliente" id="telefono_cliente" class="form-control" value="{{ old('telefono_cliente', $cliente->telefono_cliente) }}" required>
                                @error('telefono_cliente')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="web_cliente">Sitio Web</label>
                                <input type="text" name="web_cliente" id="web_cliente" class="form-control" value="{{ old('web_cliente', $cliente->web_cliente) }}" />
                                @error('web_cliente')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                           
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('clientes.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                        
                        <h3 class="mt-5">Sucursales</h3>
                        
                        <a href="{{ route('sucursales.create', ['idcliente' => $cliente->id, 'from' => 'editar_cliente']) }}" class="btn btn-primary mt-4" style="background-color: #cc0066; border-color: #cc0066;">
                            <i class="fas fa-plus-circle"></i> Agregar Sucursal
                        </a>
                                                    
                        @if($cliente->sucursal()->count() < 1)
                            <div class="alert alert-warning" role="alert">
                                Cliente sin sucursales.
                            </div>
                        @else
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre de la Sucursal</th>
                                        <th>Dirección</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cliente->sucursal as $sucursal)
                                        <tr>
                                            <td> {{ $sucursal->id }} </td>
                                            <td>{{ html_entity_decode($sucursal->nombre_sucursal) }}</td>
                                            <td>{{ html_entity_decode($sucursal->direccion_sucursal) }}</td>
                                            <td>
                                                <a href="{{ route('sucursales.edit', ['sucursale' => $sucursal->id, 'from' => 'editar_cliente']) }}" class="btn btn-sm btn-custom-warning">
                                                    <i class="fas fa-edit"></i>
                                                    Editar
                                                </a>
                                                <form action="{{ route('sucursales.destroy', $sucursal->id) }}" method="POST" class="delete-form" style="display:inline;">
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rutInput = document.getElementById('rut_cliente');

        rutInput.addEventListener('input', function (e) {
            // Eliminar caracteres no numéricos
            let rut = this.value.replace(/[^0-9Kk]/g, '');

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
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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