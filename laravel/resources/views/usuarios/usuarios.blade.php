@extends('layouts.master')

@section('content')

<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Usuarios</h2>
            <div class="d-flex align-items-center flex-wrap" style="gap: 5px;"> {{-- Agregado flex-wrap --}}
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary ms-auto"
                    style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="bi bi-plus"></i> Agregar
                </a>
                
                <a href="{{ route('usuarios.index') }}" class="btn btn-primary d-flex align-items-center justify-content-center" style="background-color: #cc6633; border-color: #cc6633; gap: 3px; flex-grow: 1; max-width: 150px;"> {{-- flex-grow, justify-content-center y max-width --}}
                    <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                </a>
                
            </div>
        </div>

        {{-- INICIO: Contenedor para formularios de registros por página y búsqueda --}}
        <div class="row mt-3">
            {{-- Columna para el formulario de registros por página --}}
            <div class="col-12 col-md-6 mb-3">
                <form action="{{ route('usuarios.index') }}"
                      method="get" id="usersPerPageForm" class="d-flex align-items-center flex-wrap"> {{-- Agregado flex-wrap --}}
                    <div class="form-group d-flex align-items-center me-3 mb-2"> {{-- Agregado mb-2 --}}
                        <label for="perPage" class="me-2 mb-0">Mostrar </label> {{-- Quitado text-nowrap --}}
                        <input type="number" name="perPage" id="perPage"
                               class="form-control form-control-sm text-center"
                               value="{{ request()->input('perPage', 10) }}" min="1" style="width: 70px;"
                               onchange="this.form.submit()">
                        <label class="ms-2 mb-0"> registros </label> {{-- Quitado text-nowrap --}}
                    </div>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>
            </div>

            {{-- Columna para el formulario de búsqueda --}}
            <div class="col-12 col-md-6 mb-3">
                <form action="{{ route('usuarios.buscar') }}" method="get" class="d-flex flex-column flex-md-row align-items-stretch" style="gap: 5px;"> {{-- flex-column, flex-md-row, align-items-stretch --}}
                    <input type="text" name="search" id="search" class="form-control mb-2 mb-md-0" {{-- mb-2 mb-md-0 --}}
                           style="border-color: #cc6633;"
                           placeholder="Buscar por nombre, correo, roles..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center" style="background-color: #cc6633; border-color: #cc6633; flex-grow: 1;"> {{-- justify-content-center, flex-grow: 1 --}}
                        <i class="fa-solid fa-magnifying-glass"></i> Buscar
                    </button>
                    @if(request('perPage'))
                        <input type="hidden" name="perPage" value="{{ request('perPage') }}">
                    @endif
                </form>
            </div>
        </div>
        {{-- FIN: Contenedor para formularios de registros por página y búsqueda --}}

        <div class="table-responsive mt-2">
            <table class="table table-bordered table-striped text-center shadow sortable-table" style="width: 100%;" >
                <thead class="table-primary">
                    <tr>
                        <th onclick="sortTable(this,0)">#ID</th>
                        <th onclick="sortTable(this,1)">Nombre</th>
                        <th onclick="sortTable(this,2)">Email</th>
                        <th onclick="sortTable(this,3)">Roles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->nombre_usuario }}</td>
                            <td>{{ $user->email_usuario }}</td>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    @foreach ($user->roles as $role)
                                        <span class="badge" style="background-color: {{ $role->color }}; color: white;">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Sin rol</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center flex-wrap" style="gap: 5px;"> {{-- flex-wrap para los botones de acción --}}
                                    <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-primary" style="background-color: #cc6633; border-color: #cc6633;">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST"
                                        class="delete-form"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            style="background-color: #d9534f; border-color: #d43f3a;">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay usuarios para mostrar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
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