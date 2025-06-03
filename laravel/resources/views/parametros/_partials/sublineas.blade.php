<details id="sublineas-section" class="sublineas-section" close>
    <summary class="sublineas-summary sublineas-summary-custom" title="contenedor de modelos de lineas">
        Sublíneas
    </summary>
    <!-- Botón Agregar Sublínea -->
    <a href="{{ route('sublineas.create') }}" class="btn btn-sm btn-custom-primary mt-3">Agregar Sublínea</a>
    <div id="sublineas-table" class="table-responsive mt-3 sublineas-table">
        <table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Línea</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sublineas as $sublinea)
                    <tr class="text-center">
                        <td>{{ $sublinea->id }}</td>
                        <td>{{ $sublinea->nombre_sublinea }}</td>
                        <td>{{ $sublinea->linea->nombre_linea ?? 'No asignada' }}</td>
                        <td class="d-flex justify-content-center" style="gap: 3px;">
                            <a href="{{ route('sublineas.show', $sublinea->id) }}" class="btn btn-sm btn-custom-info me-2">
                                <i class="fas fa-eye"></i>
                                Ver
                            </a>

                            <!-- Botón Editar -->
                            <a href="{{ route('sublineas.edit', $sublinea->id) }}" class="btn btn-sm btn-custom-warning me-2">
                                <i class="fas fa-edit text-white"></i>
                                Editar
                            </a>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('sublineas.destroy', $sublinea->id) }}" method="POST" class="delete-form">
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
                {{ $sublineas->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
            <div>
                <p class="text-muted">
                    {{ $sublineas->firstItem() }} a {{ $sublineas->lastItem() }} de {{ $sublineas->total() }} resultados.
                </p>
            </div>
        </div>
    </div>
    
</details>

<!-- Script para manejo de paginación AJAX y confirmación de eliminación con SweetAlert2 para Sublíneas -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Delegación de Eventos para Paginación AJAX de Sublíneas
        $(document).on('click', '#sublineas-section .pagination a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');

            // Cargar la tabla de forma dinámica
            loadSublineasTable(url);
        });

        function loadSublineasTable(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    // Reemplazar únicamente el contenedor de la tabla para mantener el listener
                    $('#sublineas-table').replaceWith($(data).find('#sublineas-table'));
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

        // Confirmación de Eliminación con SweetAlert2 para Sublíneas
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
