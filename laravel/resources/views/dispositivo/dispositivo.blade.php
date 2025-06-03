@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">

    <div class="container-fluid p-1">

        <div class="col">

            <div class="d-flex justify-content-between align-items-center mt-3">
                <h2>Dispositivos de Sucursales</h2>
            </div>

            {{-- INICIO: Formulario para el número de registros por página (Movido Arriba) --}}
            <div class="d-flex justify-content-start align-items-center mt-3 mb-3">
                <form action="{{ route('dispositivos.index') }}"
                      method="get" id="dispositivosPerPageForm" class="d-flex align-items-center">
                    <div class="form-group d-flex align-items-center me-3">
                        <label for="perPage" class="me-2 mb-0 text-nowrap">Mostrar </label>
                        <input type="number" name="perPage" id="perPage"
                               class="form-control form-control-sm text-center"
                               value="{{ request()->input('perPage', 7) }}" min="1" style="width: 70px;"
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
            <form action="{{ route('dispositivos.buscar') }}" method="get" class="d-flex" style="gap: 4px;">
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="Buscar por número de serie o modelo" 
                    value="{{ request('search') }}" 
                    style="border-color: #cc6633; margin-right: 4px;">
                    
                <button type="submit" class="btn btn-primary d-flex align-items-center"
                    style="background-color: #cc6633; border-color: #cc6633; display: flex; align-items: center; gap: 4px;">
                    <i class="fa-solid fa-magnifying-glass"></i>Buscar
                </button>
                {{-- Input oculto para mantener el número de registros por página al buscar --}}
                @if(request('perPage'))
                    <input type="hidden" name="perPage" value="{{ request('perPage') }}">
                @endif
            </form>

            <!-- Botones de Agregar y Eliminar Filtro -->
            <div class="d-flex align-items-center justify-content-end mt-3" style="gap: 1rem;">
                <a href="{{ route('dispositivos.create') }}" class="btn btn-secondary btn-sm"
                    style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="fa fa-plus-circle"></i> Agregar
                </a>
                <a href="{{ route('dispositivos.index') }}" class="btn btn-secondary btn-sm"
                    style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                </a>
            </div>

            <!-- Tabla de Dispositivos -->
            <div class="table-responsive mt-3">
                <table class="table table-striped sortable-table" id="dispositivos_tabledata">
                    <thead>
                        <tr>
                            <th onclick="sortTable(this,0)">Id</th>
                            <th onclick="sortTable(this,1)">Número de Serie</th>
                            <th onclick="sortTable(this,2)">Modelo</th>
                            <th onclick="sortTable(this,3)">Sucursal</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dispositivos as $dispositivo)
                            <tr>
                                <td>{{ $dispositivo->id }}</td>
                                <td>{{ $dispositivo->numero_serie_dispositivo ?? 'No disponible' }}</td>
                                <td>{{ $dispositivo->modelo->nombre_modelo ?? 'No disponible' }}</td>
                                <td>{{ $dispositivo->sucursal->nombre_sucursal ?? 'No disponible' }}</td>
                                <td class="d-flex justify-content-center" style="gap: 4px;">
                                         <div class="btn-group">
                                             <button type="button" class="btn dropdown-toggle"
                                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                 style="background-color: #cc6633; color: white; height: 38px; display: flex; align-items: center; justify-content: center;">
                                                 Acciones
                                             </button>
                                             <div class="dropdown-menu dropdown-menu-right">
 
                                                 <!-- Ver Dispositivo -->
                                                 <a class="dropdown-item" href="{{ route('dispositivos.show', $dispositivo->id) }}">
                                                     <i class="fas fa-eye" style="color: #cc0066;"></i> Ver Dispositivo
                                                 </a>
 
                                                 <!-- Editar Dispositivo -->
                                                 <a class="dropdown-item" href="{{ route('dispositivos.edit', $dispositivo->id) }}">
                                                     <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                                 </a>
 
                                                 <!-- Eliminar Dispositivo -->
                                                 <form action="{{ route('dispositivos.destroy', $dispositivo->id) }}" method="POST" class="delete-form">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar dispositivo" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                            <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                        </button>
                                                 </form>
                                             </div>
                                         </div>
                                     </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay dispositivos para mostrar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
               {{ $dispositivos->appends(request()->query())->links('pagination::bootstrap-5') }}
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
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Dispositivo creado exitosamente!',
            text: '{{ session('success') }}',
        });
    </script>
@endif

@if (session('edit_success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Dispositivo actualizado exitosamente!',
            text: '{{ session('edit_success') }}',
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
        });
    </script>
@endif



@endsection
