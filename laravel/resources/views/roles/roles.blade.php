@extends('layouts.master')

@section('content')

<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Roles</h2>
            <div class="d-flex align-items-center" style="gap: 5px;">
                <a href="{{ route('roles.create') }}" class="btn btn-primary ms-auto" style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="bi bi-plus"></i> Agregar
                </a>
                
                <a href="{{ route('roles.index') }}" class="btn btn-primary d-flex align-items-center" style="background-color: #cc6633; border-color: #cc6633; gap: 3px; width: 150px;">
                    <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                </a>
                
            </div>
        </div>

        {{-- INICIO: Formulario para el número de registros por página (Movido Arriba) --}}
        <div class="d-flex justify-content-start align-items-center mt-3 mb-3">
            <form action="{{ route('roles.index') }}"
                  method="get" id="rolesPerPageForm" class="d-flex align-items-center">
                <div class="form-group d-flex align-items-center me-3">
                    <label for="perPage" class="me-2 mb-0 text-nowrap">Mostrar </label>
                    <input type="number" name="perPage" id="perPage"
                           class="form-control form-control-sm text-center"
                           value="{{ request()->input('perPage', 10) }}" min="1" style="width: 70px;"
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

        {{-- Formulario de Búsqueda (Movido Abajo del perPage) --}}
        <form action="{{ route('roles.buscar') }}" method="GET" class="input-group mt-3">
            <input type="text" name="search" id="search" class="form-control"
                   placeholder="Buscar por nombre, descripción"
                   value="{{ request('search') }}"
                   style="border-color: #cc6633; margin-right: 4px;">
            <button type="submit" class="btn btn-primary"
                    style="background-color: #cc6633; border-color: #cc6633;">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
            {{-- Input oculto para mantener el número de registros por página al buscar --}}
            @if(request('perPage'))
                <input type="hidden" name="perPage" value="{{ request('perPage') }}">
            @endif
        </form>

        <div class="table-responsive mt-2">
            <table class="table table-bordered table-striped text-center shadow sortable-table">
                <thead class="table-primary">
                    <tr>
                        <th onclick="sortTable(this,0)">#ID</th>
                        <th onclick="sortTable(this,1)">Nombre</th>
                        <th onclick="sortTable(this,2)">Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>
                            <span class="badge" style="background-color: {{ $role->color }}; color: white;">{{ $role->name }}</span>
                        </td>
                        <td>{{ $role->description }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <form action="{{ route('roles.edit', $role->id) }}" method="GET" style="margin-right: 10px;">
                                    <button type="submit" class="btn btn-primary" style="background-color: #cc6633; border-color: #cc6633;">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                </form>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="delete-form" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay roles para mostrar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $roles->links() }}
        </div>
    </div>
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
</main>

@endsection
