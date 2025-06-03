@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Servicios</h2>
                </div>
                
                {{-- START NEW CODE: PerPage form and Total Records --}}
                <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                    {{-- Formulario para el número de registros por página --}}
                    <form action="{{ route('servicios.index') }}" method="get" id="serviciosPerPageForm" class="d-flex align-items-center">
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
                {{-- END NEW CODE --}}
                
                <form action="{{ route('servicios.buscar') }}" method="get" class="d-flex" style="gap: 4px;">
                    <input type="text" name="search" id="search" class="form-control"
                        placeholder="Buscar por ID, nombre, requerimiento o código sublinea"
                        value="{{ request()->input('search') }}" style="border-color: #cc6633; margin-right: 4px;"> {{-- Updated to request()->input() --}}
                    
                    {{-- Hidden input to maintain perPage value when searching --}}
                    <input type="hidden" name="perPage" value="{{ request()->input('perPage', 10) }}">

                    <button type="submit" class="btn btn-primary d-flex"
                        style="background-color: #cc6633; border-color: #cc6633; align-items: center; gap: 4px;"> <i class="fa-solid fa-magnifying-glass"></i>Buscar
                        </button>
                </form>

                <!-- Botones de Agregar y Eliminar Filtro -->
                <div class="d-flex align-items-center justify-content-end mt-3" style="gap: 1rem;">
                    
                    <a href="{{ route('servicios.create') }}" class="btn btn-secondary btn-sm"
                        style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa fa-plus-circle"></i> Agregar
                    </a>
                    
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary btn-sm"
                        style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                    </a>
                    
                    {{-- 
                    <button id="btnAgregarServicios" class="btn btn-secondary btn-sm" style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa fa-plus-circle" style="margin-right: 5px;"></i>crear tipo de servicio
                    </button>
                    --}}
                </div>
                
                <!-- Fondo oscuro que cubre la pantalla -->
                <div id="fondoOscuro" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 999;">
                </div>

                <!-- Formulario oculto inicialmente -->
                <div id="formularioTipoServicio" class="card" style="display: none; position: absolute; top: 155px; left: 150px; transform: translate(-50%, -50%); z-index: 1000;">
                    
                    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                        <p style="margin: 0;">Crear Nuevo Repuesto</p>

                        <!-- Botón de cerrar formulario -->
                        <button id="btnCerrarFormulario" class="btn-close" aria-label="Cerrar" style="border-radius: 100%; height: 40px; width: 40px; padding: 0;">
                            X
                        </button>
                        
                    </div>

                    <form action="{{ route('wea.creartiposervicio') }}" method="POST" style="background-color: #333333;">
                        @csrf
                        <div class="card-body">
                            <label for="nombre_tipo_servicio" class="form-label" style="color: white;">Descripcion del
                                tipo d e servicio</label>
                            <input type="text" class="form-control" id="nombre_tipo_servicio" name="nombre_tipo_servicio" required>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: center; padding-bottom: 10px;">
                            <button type="submit" class="btn btn-primary">Crear tipo de servicio</button>
                        </div>
                    </form>
                    
                </div>
                
                <script>
                    
                    // Obtener los elementos del DOM
                    const btnAgregarServicios = document.getElementById('btnAgregarServicios');
                    const formularioTipoServicio = document.getElementById('formularioTipoServicio');
                    const btnCerrarFormulario = document.getElementById('btnCerrarFormulario');
                    const fondoOscuro = document.getElementById('fondoOscuro');

                    // Mostrar el formulario y el fondo oscuro al hacer clic en el botón "Agregar Repuesto"
                    btnAgregarServicios.addEventListener('click', () => {
                        formularioTipoServicio.style.display = 'block'; // Mostrar el formulario
                        fondoOscuro.style.display = 'block'; // Mostrar el fondo oscuro
                    });

                    // Cerrar el formulario y ocultar el fondo oscuro al hacer clic en la "X"
                    btnCerrarFormulario.addEventListener('click', () => {
                        formularioTipoServicio.style.display = 'none'; // Ocultar el formulario
                        fondoOscuro.style.display = 'none'; // Ocultar el fondo oscuro
                    });

                    // Cerrar el formulario y ocultar el fondo oscuro si el usuario hace clic fuera del formulario (en el fondo oscuro)
                    fondoOscuro.addEventListener('click', () => {
                        formularioTipoServicio.style.display = 'none'; // Ocultar el formulario
                        fondoOscuro.style.display = 'none'; // Ocultar el fondo oscuro
                    });
                    
                </script>

                <!-- Tabla de Servicios -->
                <div class="table-responsive mt-3">
                    <table class="table table-striped sortable-table" id="servicios_tabledata">
                        <thead>
                            <tr>
                                <th onclick="sortTable(this,0)">Id</th>
                                <th onclick="sortTable(this,1)">Nombre Servicio</th>
                                <th onclick="sortTable(this,2)">Requerimiento o tipo Servicio</th>
                                <th onclick="sortTable(this,3)">Código Sublinea</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($servicios as $servicio)
                                <tr>
                                    <td>{{ $servicio->id }}</td>
                                    <td>{{ $servicio->nombre_servicio }}</td>
                                    <td>{{ optional($servicio->tipoServicio)->descripcion_tipo_servicio }}</td>
                                    <td>{{ optional($servicio->sublinea)->nombre_sublinea }}</td>
                                    <td class="d-flex justify-content-center" style="gap: 4px;">

                                        
                                               <div class="btn-group">
                                             <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                 style="background-color: #cc6633; color: white;">
                                                 Acciones
                                            </button>
                                       <div class="dropdown-menu dropdown-menu-right">
                                                 <!-- Ver -->
                                                 <a class="dropdown-item" href="{{ route('servicios.show', $servicio->id) }}">
                                                     <i class="fas fa-eye" style="color: #cc0066;"></i> Ver
                                                 </a>
                                                <!-- Asignar Tareas -->
                                                <a class="dropdown-item" href="{{ route('servicios.asignarTareas', $servicio->id) }}">
                                                     <i class="fas fa-tasks" style="color: #cc6633;"></i> Asignar Tareas
                                                 </a>
                                                 
                                                 
                                                 <!-- Editar -->
                                                 <a class="dropdown-item" href="{{ route('servicios.edit', $servicio->id) }}">
                                                     <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                                 </a>
                                                
                                                
 
 
                                                 <!-- Eliminar -->
                                                 <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" class="delete-form">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar servicio" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                            <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                        </button>
                                                 </form>
                                             </div>
                                         </div>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay servicios para mostrar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $servicios->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('assets/js/ordenar/GlobalTableSorter.js') }}"></script>
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
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
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@endsection
