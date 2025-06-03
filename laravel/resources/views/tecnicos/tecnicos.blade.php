@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded p-2 flex-grow-1" style="min-height: 100vh;">
    <div class="col">
        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Tecnicos</h2>
            <div class="d-flex" style="gap: 4px;">

                
                
                {{-- <button id="btnAgregarRepuesto" class="btn btn-secondary btn-sm" style="background-color: #cc6633; border-color: #cc6633; gap: 4px;">
                    <i class="fa fa-plus-circle"></i> asignar servicio
                </button> --}}
                
                <!-- Fondo oscuro que cubre la pantalla -->
                <div id="fondoOscuro" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 999;">
                </div>
                
                <div id="formularioRepuesto" class="card" style="display: none; position: absolute; top: 155px; rigth: 0; transform: translate(-50%, -50%); z-index: 1000;">
                    
                    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                        <p style="margin: 0;">Asignar Servicio a un tecnico</p>

                        <!-- Botón de cerrar formulario -->
                        <button id="btnCerrarFormulario" class="btn-close" aria-label="Cerrar" style="border-radius: 100%; height: 40px; width: 40px; padding: 0;">
                            X
                        </button>
                    </div>

                    <form action="{{ route('tecnicoyserviciowea.storetex') }}" method="POST" style="background-color: #333333;">
                        @csrf
                        
                        <div class="mb-3 d-flex" style="gap: 3px; padding-left: 5px; padding-right: 5px; padding-top: 5px;">
                            <label for="tecnico" class="form-label" style="color: white;">Tecnico</label>
                            <select class="form-select form-control" id="tecnico_ide" name="tecnicox1" required>
                                <option value="0">Seleccione un tecnico</option>
                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ html_entity_decode($tecnico->nombre_tecnico) }}</option>
                                @endforeach
                            </select>
                            <span id="errorTecnico" class="errMessage"></span>
                        </div>
                    
                        <div class="mb-3 d-flex" style="gap: 3px; padding-left: 5px; padding-right: 5px; padding-top: 5px;">
                            <label for="servicio" class="form-label" style="color: white;">Servicio</label>
                            <select class="form-select form-control" id="servicio" name="servicio" required>
                                <option value="0">Seleccione un servicio</option>
                                @foreach ($data_todos_los_servicios as $servicio)
                                    <option value="{{ $servicio->id }}">{{ html_entity_decode($servicio->nombre_servicio) }}</option>
                                @endforeach
                            </select>
                            <span id="errorServicio" class="errMessage"></span>
                        </div>
                    
                        <div style="display: flex; align-items: center; justify-content: center; padding-bottom: 10px;">
                            <button type="submit" class="btn btn-primary">Asignar</button>
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                    </form>

                </div>
                    
                <!-- Script en JavaScript para manejar la visualización del formulario y el cierre -->
                <script>
                    // Obtener los elementos del DOM
                    const btnAgregarRepuesto = document.getElementById('btnAgregarRepuesto');
                    const formularioRepuesto = document.getElementById('formularioRepuesto');
                    const btnCerrarFormulario = document.getElementById('btnCerrarFormulario');
                    const fondoOscuro = document.getElementById('fondoOscuro');

                    // Mostrar el formulario y el fondo oscuro al hacer clic en el botón "Agregar Repuesto"
                    btnAgregarRepuesto.addEventListener('click', () => {
                        formularioRepuesto.style.display = 'block'; // Mostrar el formulario
                        fondoOscuro.style.display = 'block'; // Mostrar el fondo oscuro
                    });

                    // Cerrar el formulario y ocultar el fondo oscuro al hacer clic en la "X"
                    btnCerrarFormulario.addEventListener('click', () => {
                        formularioRepuesto.style.display = 'none'; // Ocultar el formulario
                        fondoOscuro.style.display = 'none'; // Ocultar el fondo oscuro
                    });

                    // Cerrar el formulario y ocultar el fondo oscuro si el usuario hace clic fuera del formulario (en el fondo oscuro)
                    fondoOscuro.addEventListener('click', () => {
                        formularioRepuesto.style.display = 'none'; // Ocultar el formulario
                        fondoOscuro.style.display = 'none'; // Ocultar el fondo oscuro
                    });
                </script>

                

            </div>
        </div>

       <div class="d-flex justify-content-start align-items-center mt-3">
            <form action="{{ route('tecnicos.index') }}" method="get" id="tecnicosPerPageForm" class="d-flex align-items-center">
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
        
        {{-- INICIO: Formulario de búsqueda (ahora en su propia fila para ocupar todo el ancho) --}}
        <div class="mt-3 mb-3"> {{-- Margen superior e inferior para separarlo --}}
            <form action="{{ route('tecnicos.index') }}" method="get" class="d-flex w-100" style="gap: 5px;">
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="Buscar por ID, nombre, rut o correo"
                    value="{{ request('search') }}" style="border-color: #cc6633;" {{-- Eliminar margin-right si estaba --}}
                />
                <button type="submit" class="btn btn-primary d-flex"
                    style="background-color: #cc6633; border-color: #cc6633; align-items: center; gap: 3px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
                {{-- Input oculto para mantener el número de registros por página al buscar --}}
                @if(request('perPage'))
                    <input type="hidden" name="perPage" value="{{ request('perPage') }}">
                @endif
            </form>
        </div>
        
        {{-- FIN: Formulario de búsqueda --}}
            
            <div class="d-flex align-items-center justify-content-end mt-1 py-2 pr-1" style="gap: 1rem;">
                   <a href="{{ route('nuevo.metodo') }}" class="btn btn-secondary btn-sm " style="background-color: #cc6633; border-color: #cc6633;"> <i class="fa fa-plus-circle"></i>Agregar Tecnico</a>
                    <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary btn-sm "
                        style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                    </a>
                </div>
            
        
    </div>
    
        <div class="table-responsive mt-3">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            
                <table class="table table-striped sortable-table" id="tecnicos_tabledata">
                    <thead>
                        <tr>
                            <th onclick="sortTable(this,0)">Id</th>
                            <th onclick="sortTable(this,1)">Nombre</th>
                            <th onclick="sortTable(this,2)">RUT</th>
                            <th onclick="sortTable(this,3)">Teléfono</th>
                            <th onclick="sortTable(this,4)">Correo</th>
                            <th onclick="sortTable(this,5)">Precio por Hora</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tecnicos as $tecnico)
                            <tr>
                                <td>{{ $tecnico->id }}</td>
                                <td>{{ $tecnico->nombre_tecnico }}</td>
                                <td>{{ $tecnico->rut_tecnico }}</td>
                                <td>{{ $tecnico->telefono_tecnico }}</td>
                                <td>{{ $tecnico->email_tecnico }}</td>
                                <td>{{ $tecnico->precio_hora_tecnico }}</td>
                                <td class="d-flex justify-content-center" style="gap: 4px;">
                                     <div class="btn-group d-flex justify-content-center" style="gap: 3px;">
                                         <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                             style="background-color: #cc6633; color: white; height: 38px; display: flex; align-items: center; justify-content: center;">
                                             Acciones
                                         </button>
                                         <div class="dropdown-menu dropdown-menu-right">
                                             <!-- Ver Avance -->
                                             <a class="dropdown-item" href="{{ route('ver_avance_tecnicos.metodo', $tecnico->id) }}">
                                                 <i class="fas fa-eye" style="color: #cc0066;"></i> Ver
                                             </a>
 
                                            <!-- Editar -->
                                             <a class="dropdown-item" href="{{ route('tecnicos.asignar_servicios', $tecnico->id) }}">
                                                 <i class="fas fa-cogs" style="color: #6b0438;"></i> Asignar Servicios
                                             </a>
                                             <!-- Editar -->
                                             <a class="dropdown-item" href="{{ route('edit_tecnico.metodo', $tecnico->id) }}">
                                                 <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                             </a>
 
                                             <!-- Eliminar -->
                                             <form action="{{ route('tecnicos.destroy', $tecnico->id) }}" method="POST" class="delete-form">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar tecnico" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                                    <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                                </button>
                                             </form>
                                         </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay tecnicos para mostrar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $tecnicos->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>    
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/ordenar/GlobalTableSorter.js') }}"></script>
<script src="{{ asset('assets/js/mensajes/mensajes.js') }}"></script>
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
@endsection
