@extends('layouts.master')

@section('content')

<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Usuarios</h2>
            <div class="d-flex align-items-center" style="gap: 5px;">
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary ms-auto"
                    style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="bi bi-plus"></i> Agregar
                </a>
                
                <a href="{{ route('usuarios.index') }}" class="btn btn-primary d-flex align-items-center" style="background-color: #cc6633; border-color: #cc6633; gap: 3px; width: 150px;">
                    <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                </a>
                
            </div>
        </div>

        {{-- INICIO: Formulario para el número de registros por página --}}
        <div class="d-flex justify-content-start align-items-center mt-3 mb-3">
            <form action="{{ route('usuarios.index') }}"
                  method="get" id="usersPerPageForm" class="d-flex align-items-center">
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

        {{-- Formulario de Búsqueda --}}
        <form action="{{ route('usuarios.buscar') }}" method="get" class="input-group mt-3">
            <input type="text" name="search" id="search" class="form-control"
                   style="border-color: #cc6633; margin-right: 4px;"
                   placeholder="Buscar por nombre, correo, roles..."
                   value="{{ request('search') }}"> {{-- Mantener el valor de búsqueda --}}
            <button type="submit" class="btn btn-primary ml-1"
                    style="background-color: #cc6633; border-color: #cc6633;">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
            {{-- Input oculto para mantener el número de registros por página al buscar --}}
            @if(request('perPage'))
                <input type="hidden" name="perPage" value="{{ request('perPage') }}">
            @endif
        </form>

        <!-- Tabla de usuarios -->
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
                                <div class="d-flex justify-content-center align-items-center">
                                    <!-- <form action="{{ route('tecnicos.create', $user->id) }}" method="GET" style="margin-right: 10px;">
                                        <button type="submit" class="btn btn-success" style="background-color: #28a745; border-color: #28a745;">
                                            <i class="fas fa-plus"></i> Agregar Técnico
                                        </button>
                                    </form> -->

                                    <!-- Botón Editar -->
                                    <form action="{{ route('usuarios.edit', $user->id) }}" method="GET"
                                        style="margin-right: 10px;">
                                        <!-- Botón Editar -->
                                        <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-primary" style="background-color: #cc6633; border-color: #cc6633;">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>

                                    </form>
                                    <!-- Botón Eliminar -->
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
