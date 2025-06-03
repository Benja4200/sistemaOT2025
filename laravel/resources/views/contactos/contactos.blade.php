@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Contactos</h2>
                </div>
                
                 {{-- INICIO: Formulario para el número de registros por página (AGREGADO) --}}
                <div class="d-flex justify-content-start align-items-center mt-3 mb-3">
                    <form action="{{ route('contactos.index') }}" method="get" id="contactosPerPageForm" class="d-flex align-items-center">
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
                {{-- FIN: Formulario para el número de registros por página --}}

                <form action="{{ route('contactos.buscar') }}" method="get" class="d-flex" style="gap: 4px;">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Buscar por ID, nombre, departamento, cargo, email o sucursal" value="{{ request('search') }}" style="border-color: #cc6633; margin-right: 4px;">
                    <button type="submit" class="d-flex btn btn-primary" style="background-color: #cc6633; border-color: #cc6633; align-items: center; gap: 3px;"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
                </form>

                <div class="d-flex align-items-center justify-content-end mt-3" style="gap: 1rem;">
                    <a href="{{ route('contactos.create') }}" class="btn btn-secondary btn-sm" style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa fa-plus-circle"></i> Agregar
                    </a>
                    <a href="{{ route('contactos.index') }}" class="btn btn-secondary btn-sm" style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                    </a>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-striped sortable-table" id="contactos_tabledata">
                        <thead>
                            <tr>
                                <th onclick="sortTable(this,0)">id</th>
                                <th onclick="sortTable(this,1)">Nombre</th>
                                <th onclick="sortTable(this,2)">Teléfono</th>
                                <th onclick="sortTable(this,3)">Departamento</th>
                                <th onclick="sortTable(this,4)">Cargo</th>
                                <th onclick="sortTable(this,5)">Email</th>
                                <th onclick="sortTable(this,6)">Sucursal</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contactos as $contacto)
                            <tr>
                                <td>{{ $contacto->id }}</td>
                                <td>{{ $contacto->nombre_contacto }}</td>
                                <td>{{ $contacto->telefono_contacto }}</td>
                                <td>{{ $contacto->departamento_contacto }}</td>
                                <td>{{ $contacto->cargo_contacto }}</td>
                                <td>{{ $contacto->email_contacto }}</td>
                                <td>{{ $contacto->sucursal->nombre_sucursal ?? 'No disponible' }}</td>
                               <td class="d-flex justify-content-center" style="gap: 3px;">
                                 <div class="btn-group">
                                     <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                         style="background-color: #cc6633; color: white;">
                                         Acciones
                                     </button>
                                     <div class="dropdown-menu dropdown-menu-right">
                                         <!-- Ver -->
                                         <a class="dropdown-item" href="{{ route('contactos.show', $contacto->id) }}">
                                             <i class="fas fa-eye" style="color: #cc0066;"></i> Ver
                                         </a>
 
                                         <!-- Editar -->
                                         <a class="dropdown-item" href="{{ route('contactos.edit', $contacto->id) }}">
                                             <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                         </a>
 
                                         <!-- Eliminar -->
                                         <form action="{{ route('destroy_contacto.metodo', $contacto->id) }}" method="POST" class="delete-form">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar contacto" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                            <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                        </button>
                                         </form>
                                     </div>
                                 </div>
                             </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay contactos para mostrar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $contactos->appends(request()->query())->links('pagination::bootstrap-5') }}
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
