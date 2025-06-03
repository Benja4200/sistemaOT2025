@extends('layouts.master')

@section('content')

<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Órdenes</h2>
            <div class="d-flex align-items-center">
                
                <a href="{{ route('ordenes.create') }}" class="btn btn-primary ms-auto"
                    style="background-color: #cc6633; border-color: #cc6633;" title="Agregar orden">
                    <i class="fa fa-plus-circle"></i> Agregar
                </a>
                
            </div>
        </div>

        <div class="row mt-3">
            {{-- Columna para "Mostrar X registros" y Radio Buttons --}}
            <div class="col-12 col-md-6 mb-3">
                <form action="{{ route('ordenes.index') }}" method="get" id="ordenesForm" class="d-flex flex-wrap align-items-center">
                    <div class="form-group d-flex align-items-center me-3 mb-2">
                        <label for="perPage" class="me-2 mb-0">Mostrar </label>
                        <input type="number" name="perPage" id="perPage"
                               class="form-control form-control-sm text-center"
                               value="{{ request('perPage', 10) }}" min="1" style="width: 70px;"
                               onchange="this.form.submit()">
                        <label class="ms-2 mb-0"> registros </label>
                    </div>

                    <div class="d-flex flex-wrap">
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="radio" name="show_all" value="false"
                                {{ $vistaSeleccionada == 'asignadas' ? 'checked' : '' }}
                                {{ isset($tecnico) ? '' : 'disabled' }}
                                onchange="this.form.submit()">
                            <label class="form-check-label">Órdenes asignadas</label>
                        </div>

                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="radio" name="show_all" value="true"
                                {{ $vistaSeleccionada == 'todas' ? 'checked' : '' }}
                                {{ $puedeCambiarVista ? '' : 'disabled' }}
                                onchange="this.form.submit()">
                            <label class="form-check-label">Todas las órdenes</label>
                        </div>
                    </div>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                </form>
            </div>

            {{-- Columna para el formulario de búsqueda y botón de eliminar filtro --}}
            <div class="col-12 col-md-6 mb-3">
                <form action="{{ route('ordenes.buscar') }}" method="get" class="d-flex flex-column flex-md-row align-items-stretch" style="gap: 5px;">
                    <input type="hidden" name="show_all" value="{{ request()->input('show_all', 'false') }}">
                    <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">

                    <input type="text" name="search" id="search" class="form-control mb-2 mb-md-0" placeholder="Buscar por número de orden" value="{{ request()->input('search') }}" style="border-color: #cc6633;">

                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center" style="background-color: #cc6633; border-color: #cc6633; gap: px; flex-grow: 1;">
                        <i class="fa-solid fa-magnifying-glass"></i>Buscar
                    </button>

                    <a href="{{ route('ordenes.index') }}" class="btn btn-primary d-flex align-items-center justify-content-center" style="background-color: #cc6633; border-color: #cc6633; gap: 3px; flex-grow: 1;">
                        <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                    </a>
                </form>
            </div>
        </div>
        
        <div class="table-responsive mt-3" style="min-height:300px">

                <table class="table table-striped sortable-table" id="ot_tabledata">
                    <thead>
                        <tr>
                            <th onclick="sortTable(this,0)">#Orden </th>
                            <th onclick="sortTable(this,1)">Detalle</th>
                            <th onclick="sortTable(this,2)">Cliente</th>
                            <th onclick="sortTable(this,3)">Sucursal</th>
                            <th onclick="sortTable(this,4)">Servicio</th>
                            <th onclick="sortTable(this,5)">Responsable</th>
                            <th style="text-align: center;" onclick="sortTable(this,6)">Estado</th>
                            <th onclick="sortTable(this,7)">Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->numero_ot }}</td>
                            <td class="text-center">
                                <a href="#" 
                                style="color: #cc6633;" 
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="{{ htmlspecialchars($orden->descripcion_ot ?? 'No hay descripción disponible') }}" 
                                data-bs-target="#myModal">
                                <i class="far fa-file-alt fa-2x" style="color: #cc6633;"></i>
                                </a>
                            </td>
    
                            <!-- Cliente -->
                            <td>
                                @if (isset($orden->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente))
                                {{ html_entity_decode($orden->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente) }}
                                @else
                                No disponible
                                @endif
                            </td>
    
                            <!-- Sucursal -->
                            <td>
                                @if (isset($orden->contactoOt[0]->contacto->sucursal->nombre_sucursal))
                                {{ html_entity_decode($orden->contactoOt[0]->contacto->sucursal->nombre_sucursal) }}
                                @else
                                No disponible
                                @endif
                            </td>
    
                            <!-- Servicio -->
                            <td>
                                {{ html_entity_decode($orden->servicio->nombre_servicio) ?? 'No disponible' }}
                            </td>
    
                            <!-- Técnico Responsable -->
                            <td>
                                {{ $orden->tecnicoEncargado ? html_entity_decode($orden->tecnicoEncargado->nombre_tecnico) : 'No disponible' }}
                            </td>
    
                            <!-- Estado -->
                            <td class="px-4 py-2">
                                @if ($orden->estado->descripcion_estado_ot == 'Iniciada')
                                    <p style="background-color: green; color: white; padding: 5px; border-radius: 5px; text-align: center;">{{ $orden->estado->descripcion_estado_ot }}</p>
                                @elseif ($orden->estado->descripcion_estado_ot == 'Pendiente')
                                    <p style="background-color: yellow; color: black; padding: 5px; border-radius: 5px; text-align: center;">{{ $orden->estado->descripcion_estado_ot }}</p>
                                @elseif ($orden->estado->descripcion_estado_ot == 'Finalizada')
                                    <p style="background-color: red; color: white; padding: 5px; border-radius: 5px; text-align: center;">{{ $orden->estado->descripcion_estado_ot }}</p>
                                @elseif ($orden->estado->descripcion_estado_ot == 'Creada')
                                    <p style="background-color: gray; color: white; padding: 5px; border-radius: 5px; text-align: center;">{{ $orden->estado->descripcion_estado_ot }}</p>
                                @endif
                            </td>
    
                            <!-- Fecha de Creación -->
                            <td style="width:10%">
                                {{ $orden->created_at ? date('d-m-Y', strtotime($orden->created_at)) : 'No disponible' }}
                            </td>
    
                            <!-- Acciones -->
                             <td class="d-flex justify-content-center" style="gap: 4px;">
                                 <!-- Dropdown para acciones -->
                                 <div class="dropdown">
                                     <button class="btn dropdown-toggle" type="button" id="accionesDropdown{{ $orden->numero_ot }}" data-toggle="dropdown" aria-expanded="false" style="background-color: #cc6633; color: white;">
                                         Acciones
                                     </button>
                                     <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accionesDropdown{{ $orden->numero_ot }}">
                             
                                         <!-- Fila superior: Botones Avances, Detalle y Editar -->
                                         <!-- Botón Avances -->
                                         <li>
                                             <a class="dropdown-item" href="{{ route('editor_avance',$orden->numero_ot) }}" title="Avances de la OT">
                                                 <i class="fas fa-plus text-success"></i> Avances
                                             </a>
                                         </li>
                                        
                                        <!-- Botón Detalle -->
                                        <li>
                                             <a class="dropdown-item" href="{{ route('ordenes.show', $orden->numero_ot) }}" data-bs-toggle="modal" data-bs-target="#myModal" onclick="showOrderDetails({{ $orden }})" title="Detalle de la Orden">
                                                 <i class="fas fa-eye text-danger"></i> Detalle
                                             </a>
                                         </li>
                            
                                        <!-- Botón Editar -->
                                        <li>
                                             <a class="dropdown-item" href="{{ route('ordenes.edit', $orden->numero_ot) }}" title="Editar orden">
                                                 <i class="fas fa-edit text-warning"></i> Editar
                                             </a>
                                         </li>
                                    
                                        <!-- Botónes para imprimir -->
                                        <li>
                                             <a class="dropdown-item" href="{{ route('imprimirOt', [$orden->numero_ot, 'Sinfirma']) }}" target="_blank" title="Imprimir sin firma">
                                                 <i class="fas fa-print text-secondary"></i> Imprimir
                                             </a>
                                         </li>
                                         <li>
                                             <a class="dropdown-item" href="{{ route('imprimirOt', [$orden->numero_ot, 'firmado']) }}" target="_blank" title="Imprimir con firma">
                                                 <i class="fas fa-print text-secondary"></i> Imprimir Firmado
                                             </a>
                                         </li>
                                         <li>
                                             <a class="dropdown-item" href="{{ route('vistaFirmaCliente', $orden->numero_ot) }}" target="_blank" title="Imprimir con firma">
                                                 <i class="fas fa-print text-secondary"></i> Imprimir con firma del cliente
                                             </a>
                                         </li>
                                    
                                        <!-- Formulario Eliminar -->
                                        <li>
                                             <form action="{{ route('ordenes.destroy', $orden->numero_ot) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar orden" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                                <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                            </button>
                                        </form>
                                         </li>
                                        
                                     </ul>
                                </div>
                            </td>
                            
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay ordenes para mostrar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $ordenes->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

        <!-- Modal Structure -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Detalle de la OT</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modal-description"></p>
                        <a href="#" class="btn btn-danger"><i class="far fa-file-pdf fa-lg"></i> Generar
                            PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function showOrderDetails(order) {
        // Aquí podrías mostrar información real del pedido
        document.getElementById('modal-description').innerText = "Aquí irá la información detallada de la orden.";
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Selecciona todos los elementos que tienen data-bs-toggle="tooltip"
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        
        // Inicializa cada tooltip
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('delete'))
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('delete') }}',
                confirmButtonText: 'Aceptar'
            });
        @endif
    });
</script>

<script src="{{ asset('assets/js/ordenar/GlobalTableSorter.js') }}"></script>

@endsection
