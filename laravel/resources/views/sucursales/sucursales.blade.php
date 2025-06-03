@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Sucursales</h2>
                </div>

                {{-- Formulario para perPage (ARRIBA y a la IZQUIERDA) --}}
                <div class="d-flex justify-content-start align-items-center mt-3 mb-3">
                    <form action="{{ route('sucursales.index') }}" method="get" id="sucursalesPerPageForm" class="d-flex align-items-center">
                        <div class="form-group d-flex align-items-center me-3">
                            <label for="perPage" class="me-2 mb-0 text-nowrap">Mostrar </label>
                            <input type="number" name="perPage" id="perPage"
                                   class="form-control form-control-sm text-center"
                                   value="{{ request()->input('perPage', 6) }}" min="1" style="width: 70px;"
                                   onchange="this.form.submit()">
                            <label class="ms-2 mb-0 text-nowrap"> registros </label>
                        </div>
                        {{-- Input oculto para mantener el término de búsqueda al cambiar perPage --}}
                        <input type="hidden" name="search" value="{{ request()->input('search') }}">
                    </form>
                </div>

                <div class="d-flex align-items-center mt-0">
                    <form action="{{ route('sucursales.buscar') }}" method="get" class="input-group">
                        <input type="text" name="search" id="search" class="form-control mr-1" style="border-color: #cc6633; margin-right: 4px;"
                            placeholder="Buscar por ID, nombre, dirección o cliente"
                            value="{{ request()->input('search') }}"> {{-- Use request()->input() --}}
                        
                        {{-- Input oculto para mantener el valor de perPage al enviar el formulario de búsqueda --}}
                        <input type="hidden" name="perPage" value="{{ request()->input('perPage', 6) }}">
                            
                        <button type="submit" class="btn btn-primary"
                            style="background-color: #cc6633; border-color: #cc6633;"><i
                                class="fa-solid fa-magnifying-glass"></i> Buscar</button>
                    </form>
                </div>

                <div class="d-flex align-items-center justify-content-end mt-3" style="gap: 1rem;">
                    <a href="{{ route('sucursales.create') }}" class="btn btn-secondary btn-sm"
                        style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa fa-plus-circle"></i> Agregar
                    </a>
                    <a href="{{ route('sucursales.index') }}" class="btn btn-secondary btn-sm"
                        style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                    </a>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-striped sortable-table" id="sucursales_tabledata">
                        <thead>
                            <tr>
                                <th onclick="sortTable(this,0)">Id</th>
                                <th onclick="sortTable(this,1)">Nombre</th>
                                <th onclick="sortTable(this,2)">Teléfono</th>
                                <th onclick="sortTable(this,3)">Dirección</th>
                                <th onclick="sortTable(this,4)">Cliente</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sucursales as $sucursal) {{-- Changed to @forelse for empty state --}}
                                <tr>
                                    <td>{{ $sucursal->id ?? 'No disponible' }}</td>
                                    <td>{{ $sucursal->nombre_sucursal ?? 'No disponible' }}</td>
                                    <td>{{ $sucursal->telefono_sucursal ?? 'No disponible' }}</td>
                                    <td>{{ $sucursal->direccion_sucursal ?? 'No disponible' }}</td>
                                    <td>{{ $sucursal->cliente->nombre_cliente ?? 'No disponible' }}</td>
                                    <td class="d-flex justify-content-center" style="gap: 4px;">
                                             <div class="btn-group">
                                                 <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                     style="background-color: #cc6633; color: white;">
                                                     Acciones
                                                 </button>
                                                 <div class="dropdown-menu dropdown-menu-right">
                                                     <a class="dropdown-item" href="{{ route('sucursales.show', $sucursal->id) }}">
                                                         <i class="fas fa-eye" style="color: #cc0066;"></i> Ver
                                                     </a>
 
                                                     <a class="dropdown-item" href="{{ route('sucursales.edit', $sucursal->id) }}">
                                                         <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                                     </a>
 
                                                     <form action="{{ route('sucursales.destroy', $sucursal->id) }}" method="POST" class="delete-form">
                                                         @csrf
                                                         @method('DELETE')
                                                         <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar sucursal" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                            <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                        </button>
                                                     </form>
                                                 </div>
                                             </div>
                                         </td>
                                </tr>
                            @empty {{-- Add empty state for no results --}}
                                <tr>
                                    <td colspan="6" class="text-center">No hay sucursales para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $sucursales->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
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
    @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $errors->first() }}',
                    confirmButtonText: 'Aceptar'
                });
            </script>
        @endif
@endsection