<details id="subcategorias-section" class="subcategorias-section" close>
    <summary class="subcategorias-summary subcategorias-summary-custom">
        Subcategorías
    </summary>
    <a href="{{ route('subcategoria.create') }}" class="btn btn-sm btn-custom-primary mt-3">Agregar Subcategoría</a>
    <div id="subcategorias-table" class="table-responsive mt-3 subcategorias-table">
        <table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subcategorias as $subcategoria)
                    <tr class="text-center">
                        <td>{{ $subcategoria->id }}</td>
                        <td>{{ $subcategoria->nombre_subcategoria }}</td>
                        <td>{{ $subcategoria->categoria->nombre_categoria ?? 'No asignada' }}</td>
                        <td>
                            <!-- Botón Ver -->
                            <a href="{{ route('subcategoria.show', $subcategoria->id) }}" class="btn btn-sm btn-custom-info">
                                <i class="fas fa-eye"></i>
                                Ver
                            </a>

                            <!-- Botón Editar -->
                            <a href="{{ route('subcategoria.edit', $subcategoria->id) }}" class="btn btn-sm btn-custom-warning">
                                <i class="fas fa-edit"></i>
                                Editar
                            </a>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('subcategoria.destroy', $subcategoria->id) }}" method="POST" class="delete-form" style="display:inline;">
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

        <!-- Paginación y Información de Resultados -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                {{ $subcategorias->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
            <div>
                <p class="text-muted">
                    {{ $subcategorias->firstItem() }} a {{ $subcategorias->lastItem() }} de {{ $subcategorias->total() }} resultados.
                </p>
            </div>
        </div>
    </div>
    <!-- Botón Agregar Subcategoría -->
    <div class="ml-2">
        @include('parametros._partials.lineas')
    </div>
    
</details>

<!-- Script para manejo de paginación AJAX para Subcategorías y confirmación de eliminación con SweetAlert2 -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Delegación de Eventos para Paginación AJAX de Subcategorías
        $(document).on('click', '#subcategorias-section .pagination a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');

            // Cargar la tabla de forma dinámica
            loadSubcategoriasTable(url);
        });

        function loadSubcategoriasTable(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    // Reemplazar únicamente el contenedor de la tabla para mantener otras secciones intactas
                    $('#subcategorias-table').replaceWith($(data).find('#subcategorias-table'));
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

        // Confirmación de Eliminación con SweetAlert2 para Subcategorías
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
