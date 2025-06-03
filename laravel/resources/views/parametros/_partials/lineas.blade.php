<details id="lineas-section" class="lineas-section" close>
    <summary class="lineas-summary subcategorias-summary-custom">
        Líneas
    </summary>
    <a href="{{ route('lineas.create') }}" class="btn btn-sm btn-custom-primary mt-3">Agregar Línea</a>
    <div id="lineas-table" class="table-responsive mt-3 lineas-table">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Subcategoría</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lineas as $linea)
                    <tr class="">
                        <td class="text-center">{{ $linea->id }}</td>
                        <td class="text-center">{{ $linea->nombre_linea }}</td>
                        <td class="text-center">{{ $linea->subcategoria->nombre_subcategoria ?? 'No asignada' }}</td>
                        <td class="text-center">
                            <!-- Botón Ver -->
                            <a href="{{ route('lineas.show', $linea->id) }}" class="btn btn-sm btn-custom-info">
                                <i class="fas fa-eye"></i>
                                Ver
                            </a>

                            <!-- Botón Editar -->
                            <a href="{{ route('lineas.edit', $linea->id) }}" class="btn btn-sm btn-custom-warning">
                                <i class="fas fa-edit text-white"></i>
                                Editar
                            </a>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('lineas.destroy', $linea->id) }}" method="POST" class="delete-form" style="display:inline;">
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
                {{ $lineas->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
            <div>
                <p class="text-muted">
                    {{ $lineas->firstItem() }} a {{ $lineas->lastItem() }} de {{ $lineas->total() }} resultados.
                </p>
            </div>
        </div>
    </div>
    <!-- Botón Agregar Línea -->
    <div class="ml-3">
        @include('parametros._partials.sublineas')
    </div>
</details>

<!-- Script para manejo de paginación AJAX y confirmación de eliminación con SweetAlert2 para Líneas -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Delegación de Eventos para Paginación AJAX de Líneas
        $(document).on('click', '#lineas-section .pagination a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');

            // Cargar la tabla de forma dinámica
            loadLineasTable(url);
        });

        function loadLineasTable(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    // Reemplazar únicamente el contenedor de la tabla para mantener otras secciones intactas
                    $('#lineas-table').replaceWith($(data).find('#lineas-table'));
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

        // Confirmación de Eliminación con SweetAlert2 para Líneas
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
