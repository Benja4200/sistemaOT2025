@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Editar Categoría</h2>
            <div>
                <!-- Botón Cancelar -->
                <a href="{{ route('parametros.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                    <i class="fas Example of arrow-left fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                Editar Información de la Categoría
            </div>
            <div class="card-body">
            <form action="{{ route('categoria.update', $categoria->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nombre_categoria">Nombre de la Categoría</label>
                    <input type="text" name="nombre_categoria" class="form-control"
                        value="{{ $categoria->nombre_categoria }}" required>
                </div>
                <!-- Botónes -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <!-- Botón Guardar -->
                    <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
    
                    <!-- Botón Cancelar -->
                    <a href="{{ route('parametros.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas fa-times-circle"></i> Cancelar
                    </a>
                </div>
            </form>
    
            <!-- Tabla de Subcategorías -->
            <h3 class="mt-5">Subcategorías Asociadas</h3>
            <a href="{{ route('subcategoria.crear',$categoria->id) }}" class="btn btn-sm btn-custom-primary mt-3 mb-3" style="background-color: #cc0066; border-color: #cc0066;">
                 <i class="fas fa-plus-circle"></i> Agregar Subcategoría
            </a>
            
            @if($subcategorias->isEmpty())
                <div class="alert alert-warning" role="alert">
                    Categoria sin Subcategorias asociadas.
                </div>
            @else
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Subcategoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subcategorias as $subcategoria)
                            <tr>
                                <td> {{ $subcategoria->id }} </td>
                                <td>{{ html_entity_decode($subcategoria->nombre_subcategoria) }}</td>
                                <td>
                                    <a href="{{ route('subcategoria.edit', [ $subcategoria->id, $categoria->id]) }}" class="btn btn-sm btn-custom-warning">
                                        <i class="fas fa-edit"></i>
                                        Editar
                                    </a>
        
                                    <form action="{{ route('subcategoria.destroy', $subcategoria->id) }}" method="POST" class="delete-form" style="display:inline;">
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
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    function confirmDelete(button) {
        const form = button.closest('form');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás recuperar esta subcategoría después de eliminarla!",
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
@endsection