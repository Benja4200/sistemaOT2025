@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Editar Subcategoría</h2>
                <div>
                    <!-- Botón Cancelar -->
                    <a href="{{ route('categoria.edit', $subcategoria->cod_categoria) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    Editar Información de la Subcategoría
                </div>
                <div class="card-body">
                    <form action="{{ route('subcategoria.update', $subcategoria->id) }}" method="POST">
                        @csrf
                        @method('PUT')
        
                        <!-- Nombre de la Subcategoría -->
                        <div class="form-group">
                            <label for="nombre_subcategoria">Nombre de la Subcategoría</label>
                            <input type="text" name="nombre_subcategoria" class="form-control"
                                value="{{ $subcategoria->nombre_subcategoria }}" required>
                        </div>
        
                        <!-- Seleccionar Categoría -->
                        {{-- 
                        <div class="form-group">
                            <label for="cod_categoria">Categoría</label>
                            <select name="cod_categoria" id="cod_categoria" class="form-control" required>
                                <option value="">Seleccione una Categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ $subcategoria->cod_categoria == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre_categoria }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        --}}
                        <input type="hidden" name="cod_categoria" value="{{ $subcategoria->cod_categoria }}">
                        <!-- Botones -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <!-- Botón Guardar -->
                            <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
        
                            <!-- Botón Cancelar -->
                            <a href="{{ route('categoria.edit', $subcategoria->cod_categoria) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                <i class="fas fa-times-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                    <h3 class="mt-5">Líneas Asociadas</h3>
                    <a href="{{ route('lineas.crear',$subcategoria->id) }}" class="btn btn-sm btn-custom-primary mt-3 mb-3" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas fa-plus-circle"></i> Agregar Línea
                    </a>
                    @if($lineas->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Subcategoría sin líneas asociadas.
                        </div>
                    @else
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de la Línea</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lineas as $linea)
                                    <tr>
                                        <td> {{ $linea->id }} </td>
                                        <td>{{ html_entity_decode($linea->nombre_linea) }}</td>
                                        <td>
                                            <a href="{{ route('lineas.edit', $linea->id) }}" class="btn btn-sm btn-custom-warning">
                                                <i class="fas fa-edit text-white"></i>
                                                Editar
                                            </a>
                    
                                            <form action="{{ route('lineas.destroy', $linea->id) }}" method="POST" class="delete-form" style="display:inline;">
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
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    function confirmDelete(button) {
        const form = button.closest('form');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás recuperar esta Linea después de eliminarla!",
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
