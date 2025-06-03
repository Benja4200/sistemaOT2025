@extends('layouts.master')

@section('content')
<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="col">

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2>Marcas</h2>
        </div>

        {{-- INICIO: Formulario para el número de registros por página --}}
        <div class="d-flex justify-content-start align-items-center mt-3">
            {{-- Este formulario también apunta al método index --}}
            <form action="{{ route('marcas.index') }}"
                  method="get" id="marcasPerPageForm" class="d-flex align-items-center">
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
       <div>
            {{-- El formulario de búsqueda apunta al método index para consolidar la lógica --}}
            <form action="{{ route('marcas.index') }}" method="get" class="input-group d-flex" style="gap: 3px;">
                <input type="text" name="search" id="search" class="form-control"
                       placeholder="Buscar por ID o nombre de marca"
                       value="{{ request('search') }}" style="border-color: #cc6633; margin-right: 4px;">
                <button type="submit" class="btn btn-primary"
                        style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
                {{-- Input oculto para mantener el número de registros por página al buscar --}}
                @if(request('perPage'))
                    <input type="hidden" name="perPage" value="{{ request('perPage') }}">
                @endif
            </form>
        </div>

        
        {{-- FIN: Formulario para el número de registros por página --}}

        <!-- Botones -->
        <div class="d-flex align-items-center justify-content-end mt-3" style="gap: 1rem;">
            <a href="{{ route('marcas.create') }}" class="btn btn-secondary btn-sm"
               style="background-color: #cc6633; border-color: #cc6633;">
                <i class="fa fa-plus-circle"></i> Agregar
            </a>
            <a href="{{ route('marcas.index') }}" class="btn btn-secondary btn-sm"
               style="background-color: #cc6633; border-color: #cc6633;">
                <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
            </a>
        </div>
        <!-- Tabla -->
        <div class="table-responsive mt-3">
            <table class="table table-striped sortable-table" id="marcas_tabledata">
                <thead>
                    <tr>
                        <th onclick="sortTable(this,0)">ID</th>
                        <th onclick="sortTable(this,1)">Nombre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marcas as $marca)
                        <tr>
                            <td>{{ $marca->id }}</td>
                            <td>{{ $marca->nombre_marca }}</td>
                            <td class="d-flex justify-content-center" style="gap: 4px;">
                                
                                <div class="btn-group">
                                    <button type="button" class="btn dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        style="background-color: #cc6633; color: white; height: 38px; display: flex; align-items: center; justify-content: center;">
                                        Acciones
                                    </button>
                                <div class="dropdown-menu dropdown-menu-right">
 
                                         <!-- Ver Marca -->
                                         <a class="dropdown-item" href="{{ route('marcas.show', $marca->id) }}">
                                             <i class="fas fa-eye" style="color: #cc0066;"></i> Ver Marca
                                         </a>
 
                                         <!-- Editar Marca -->
                                         <a class="dropdown-item" href="{{ route('marcas.edit', $marca->id) }}">
                                             <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                         </a>
 
                                         <!-- Eliminar Marca -->
                                         <form action="{{ route('marcas.destroy', $marca->id) }}" method="POST" class="delete-form">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar marca" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                            <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                        </button>
                                         </form>
                                     </div>
                                 </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No hay marcas para mostrar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
        {{ $marcas->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

    </div>
</main>

<!-- Scripts -->
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
            console.log("Se dispara");
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

@endsection