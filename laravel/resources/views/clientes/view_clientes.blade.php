@extends('layouts.master')

@section('content')


    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center mt-3">
                <h2>Clientes</h2>
            </div>
            
            {{-- INICIO: Formulario para el número de registros por página (AGREGADO) --}}
            <div class="d-flex justify-content-start align-items-center mt-3 mb-3">
                <form action="{{ route('clientes.index') }}" method="get" id="clientesPerPageForm" class="d-flex align-items-center">
                    <div class="form-group d-flex align-items-center me-3">
                        <label for="perPage" class="me-2 mb-0 text-nowrap">Mostrar </label>
                        <input type="number" name="perPage" id="perPage"
                               class="form-control form-control-sm text-center"
                               value="{{ request()->input('perPage', 10) }}" min="1" style="width: 70px;"
                               onchange="this.form.submit()">
                        <label class="ms-2 mb-0 text-nowrap"> registros </label>
                    </div>
                    {{-- Input oculto para mantener el término de búsqueda al cambiar perPage --}}
                    <input type="hidden" name="search" value="{{ request()->input('search') }}">
                </form>
            </div>
            {{-- FIN: Formulario para el número de registros por página --}}

            <div>
                <form action="{{ route('clientes.buscarcliente') }}" method="get" class="input-group d-flex"
                    style="gap: 3px;">
                    <input type="text" name="search" id="search" class="form-control" style="border-color: #cc6633; margin-right: 4px;"
                        placeholder="Buscar por ID, nombre, RUT, correo o web" value="{{ request()->input('search') }}"> {{-- Actualizado a request()->input() --}}
                    
                    {{-- INICIO: Input oculto para mantener el valor de perPage en el formulario de búsqueda (AGREGADO) --}}
                    <input type="hidden" name="perPage" value="{{ request()->input('perPage', 10) }}"> 
                    {{-- FIN: Input oculto para mantener el valor de perPage --}}

                    <button type="submit" class="btn btn-primary"
                        style="background-color: #cc6633; border-color: #cc6633;"><i
                                class="fa-solid fa-magnifying-glass"></i> Buscar</button>
                </form>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-3" style="gap: 1rem;">
                <a href="{{ route('clientes.create') }}" class="btn btn-secondary btn-sm"
                    style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="fa fa-plus-circle"></i> Agregar
                </a>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm"
                    style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                </a>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-striped sortable-table" id="clientes_tabledata">
                    <thead>
                        <tr>
                            <th onclick="sortTable(this,0)">Id</th>
                            <th onclick="sortTable(this,1)">Nombre</th>
                            <th onclick="sortTable(this,2)">Rut</th>
                            <th onclick="sortTable(this,3)">Correo</th>
                            <th onclick="sortTable(this,4)">Teléfono</th>
                            <th onclick="sortTable(this,5)">Web</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientesxw as $cliente)
                            <tr>
                                <td>{{ $cliente->id }}</td>
                                <td>{{ $cliente->nombre_cliente }}</td>
                                <td>{{ $cliente->rut_cliente }}</td>
                                <td>{{ $cliente->email_cliente }}</td>
                                <td>{{ $cliente->telefono_cliente }}</td>
                                <td>{{ $cliente->web_cliente }}</td>
                                <td class="d-flex justify-content-center" style="gap: 4px;">
    
                                    <div class="btn-group">
                                           <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #cc6633; color: white;">
                                               Acciones
                                           </button>
                                           <div class="dropdown-menu dropdown-menu-right">
                                               <a class="dropdown-item" href="{{ route('clientes.show', $cliente->id) }}">
                                                   <i class="fas fa-eye" style="color: #cc0066;"></i> Ver
                                               </a>
                                               <a class="dropdown-item" href="{{ route('clientes.edit', $cliente->id) }}">
                                                   <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                               </a>


                                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST"  class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar cliente" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                                <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                            </button>
                                        </form>
                                           </div>
                                         </div>
                                     </td>

                            </tr>
                         @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay clientes para mostrar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $clientesxw->appends(request()->query())->links('pagination::bootstrap-5') }}
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