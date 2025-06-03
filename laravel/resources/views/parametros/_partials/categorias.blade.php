<details id="categorias-section" class="categorias-section m-2" close>
    <summary class="categorias-summary categorias-summary-custom">
        Categorías
    </summary>
    <!-- Botón Agregar Categoría -->
    <a href="{{ route('categoria.create') }}" class="btn btn-sm btn-custom-primary mt-3">Agregar Categoría</a>
    <div id="categorias-table" class="table-responsive mt-3 categorias-table">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categorias as $categoria)
                    <tr class="text-center">
                        <td>{{ $categoria->id }}</td>
                        <td>{{ $categoria->nombre_categoria }}</td>
                        <td>
                            <!-- Botón Ver -->
                            <a href="{{ route('categoria.show', $categoria->id) }}" class="btn btn-sm btn-custom-info">
                                <i class="fas fa-eye"></i>
                                Ver
                            </a>

                            <!-- Botón Editar -->
                            <a href="{{ route('categoria.edit', $categoria->id) }}" class="btn btn-sm btn-custom-warning">
                                <i class="fas fa-edit"></i>
                                Editar
                            </a>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('categoria.destroy', $categoria->id) }}" method="POST" class="delete-form" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-custom-danger">
                                    <i class="fas fa-trash-alt"></i>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                {{ $categorias->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
            <div>
                <p class="text-muted">
                    {{ $categorias->firstItem() }} a {{ $categorias->lastItem() }} de {{ $categorias->total() }} resultados.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Subcategorías -->
    <div class="ml-1">
        @include('parametros._partials.subcategorias')
    </div>
    
</details>


<!-- Script para manejo de paginación AJAX para Categorías -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Delegación de Eventos para Paginación AJAX de Categorías
        $(document).on('click', '#categorias-section .pagination a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');

            // Cargar la tabla de forma dinámica
            loadCategoriasTable(url);
        });

        function loadCategoriasTable(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    // Reemplazar únicamente el contenedor de la tabla para mantener otras secciones intactas
                    $('#categorias-table').replaceWith($(data).find('#categorias-table'));
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar los datos. Inténtalo de nuevo.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }

        // Confirmación de Eliminación con SweetAlert2 para Categorías
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            let form = this;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
