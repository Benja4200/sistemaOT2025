@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">

    <div class="col pb-1">

        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2>Tareas</h2>
        </div>
        
        {{-- INICIO: Formulario para el número de registros por página --}}
        <div class="d-flex justify-content-start align-items-center mt-3">
            {{-- La acción del formulario de perPage debe ir a la ruta de búsqueda si ya hay un término,
                 o al index si no hay búsqueda activa, para mantener los filtros. --}}
            <form action="{{ request('search') ? route('buscar_tarea.metodo') : route('tareas.index') }}"
                  method="get" id="tareasPerPageForm" class="d-flex align-items-center">
                <div class="form-group d-flex align-items-center me-3">
                    <label for="perPage" class="me-2 mb-0 text-nowrap">Mostrar </label>
                    <input type="number" name="perPage" id="perPage"
                           class="form-control form-control-sm text-center"
                           value="{{ request()->input('perPage', 6) }}" min="1" style="width: 70px;"
                           onchange="this.form.submit()">
                    <label class="ms-2 mb-0 text-nowrap"> registros </label>
                </div>
                {{-- Input oculto para mantener el término de búsqueda al cambiar perPage --}}
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </div>
        {{-- FIN: Formulario para el número de registros por página --}}
        
        {{-- Formulario de Búsqueda --}}
        <div>
            <form action="{{ route('buscar_tarea.metodo') }}" method="get" class="d-flex mt-3" style="gap: 5px;">
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="Buscar por nombre de tarea o nombre de subcategoria"
                    value="{{ request('search') }}"
                    style="border-color: #cc6633; margin-right: 4px;">
                <button type="submit" class="btn btn-primary d-flex"
                    style="background-color: #cc6633; border-color: #cc6633; align-items: center; gap: 2px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
                {{-- Input oculto para mantener el número de registros por página al buscar --}}
                @if(request('perPage'))
                    <input type="hidden" name="perPage" value="{{ request('perPage') }}">
                @endif
            </form>
        </div>

        <!-- Botones de Agregar y Eliminar Filtro -->
        <div class="d-flex align-items-center justify-content-end mt-3" style="gap: 1rem;">
            <a href="{{ route('redirec_agre_tarea.metodo') }}" class="btn btn-secondary btn-sm"
                style="background-color: #cc6633; border-color: #cc6633;">
                <i class="fa fa-plus-circle"></i> Agregar
            </a>
            <a href="{{ route('tareas.index') }}" class="btn btn-secondary btn-sm"
                style="background-color: #cc6633; border-color: #cc6633;">
                <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
            </a>
        </div>

        <!-- Tabla de Tareas -->
        <div class="table-responsive mt-3">
            <table class="table table-striped sortable-table" id="tareas_tabledata">
                <thead>
                    <tr>
                        <th onclick="sortTable(this,0)">Id</th>
                        <th onclick="sortTable(this,1)">Nombre Tarea</th>
                        {{-- <th onclick="sortTable(this,2)">Nombre Servicio</th> --}}
                        <th onclick="sortTable(this,2)">Nombre Subcategoria</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tareas as $tarea)
                        <tr>
                            <td>{{ $tarea->id }}</td>
                            <td>{{ $tarea->nombre_tarea }}</td>
                            {{-- <td>{{ optional($tarea->servicio)->nombre_servicio }}</td> --}}
                            <td>{{ optional($tarea->subcategoria)->nombre_subcategoria }}</td> 
                            <td class="d-flex justify-content-center" style="gap: 4px;">
                                 <div class="btn-group d-flex justify-content-center" style="gap: 3px;">
                                     <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                         style="background-color: #cc6633; color: white; height: 38px; display: flex; align-items: center; justify-content: center;">
                                         Acciones
                                     </button>
                                     <div class="dropdown-menu dropdown-menu-right">
                                         <!-- Ver Avance -->
                                         <a class="dropdown-item" href="{{ route('ver_avance_tarea.metodo', $tarea->id) }}">
                                             <i class="fas fa-eye" style="color: #cc0066;"></i> Ver
                                         </a>
 
                                         <!-- Editar -->
                                         <a class="dropdown-item" href="{{ route('tareas.edit', $tarea->id) }}">
                                             <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                         </a>
 
                                         <!-- Eliminar -->
                                         <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="delete-form">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar tarea" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                            <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                        </button>
                                         </form>
                                     </div>
                                </div>
                                
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay tareas para mostrar.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $tareas->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
<script src="{{ asset('assets/js/ordenar/GlobalTableSorter.js') }}"></script>
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Evitar el envío del formulario

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'No, cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Enviar el formulario si se confirma
                }
            });
        });
    });
</script>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@endsection
