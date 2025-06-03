@extends('layouts.master')

@section('content')
<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    
    <div class="container-fluid">
       <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                    <h4>Datos de Parámetros</h4>
                </div>
                <form method="GET" action="{{ route('parametros.index') }}" class="mb-1">
                    {{-- 
                    <div class="d-flex align-items-center mb-3">
                        <div class="form-group mb-0 me-3 d-flex align-items-center">
                            <label for="perPage" class="me-2 mb-0">Mostrar </label>
                            <select name="perPage" id="perPage" class="form-control m-1" onchange="this.form.submit()">
                                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <label class="me-2 mb-0"> registros </label>
                        </div>
                    </div>
                    --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="form-group mb-0 me-3 d-flex align-items-center">
                            <label for="perPage" class="me-2 mb-0">Mostrar </label>
                            <input type="number" name="perPage" id="perPage" class="form-control m-1" 
                                   value="{{ request('perPage') ? request('perPage') : 5 }}" min="1" 
                                   onkeypress="if(event.key === 'Enter'){ this.form.submit(); }" 
                                   onblur="this.form.submit();" 
                                   style="width: 80px;">
                            <label class="me-2 mb-0"> registros </label>
                        </div>
                    </div>
                    <!-- Barra de búsqueda en el mismo formulario -->
                    <div class="d-flex align-items-center">
                        <input type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Buscar por categoria, subcategoria . . ."
                        value="{{ request('search') }}"
                        style="border-color: #cc6633; margin-right: 4px;">
                        
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #cc6633; border-color: #cc6633; display: flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-magnifying-glass"></i>Buscar 
                            </button>
                        </div>
                    </div>
        
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="order" value="{{ request('order') }}">
                </form>
               <!-- Sección: Categorías, Subcategorías, Líneas y Sublíneas -->
               <div class="d-flex align-items-center justify-content-end mt-1 py-2 pr-1" style="gap: 1rem;">
                   <a href="{{ route('categoria.create') }}" class="btn btn-secondary btn-sm " style="background-color: #cc6633; border-color: #cc6633;"> <i class="fa fa-plus-circle"></i>Agregar Categoría</a>
                    <a href="{{ route('parametros.index') }}" class="btn btn-secondary btn-sm "
                        style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                    </a>
                </div>
               
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">Categorías, Subcategorías, Líneas y Sublíneas para equipos</h5>
                    </div>
                    <div class="card-body">
                        @if($paginatedItems->isEmpty())
                            <div class="alert alert-warning" role="alert">
                                No se ha encontrado ningún resultado para su búsqueda.
                            </div>
                        @else
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align:center; width: 23%;">
                                            <a href="{{ route('parametros.index', ['sort' => 'categoria', 'order' => request('order') === 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'perPage' => request('perPage')]) }}" style="display: block; text-decoration: none; color: inherit;">
                                                Categoría
                                                @if(request('sort') === 'categoria')
                                                    @if(request('order') === 'asc')
                                                        <i class="fas fa-arrow-up"></i>
                                                    @else
                                                        <i class="fas fa-arrow-down"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-arrows-alt-v"></i> <!-- Flechas verticales -->
                                                @endif
                                            </a>
                                        </th>
                                        <th style="text-align:center; width: 23%;">
                                            <a href="{{ route('parametros.index', ['sort' => 'subcategoria', 'order' => request('order') === 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'perPage' => request('perPage')]) }}" style="display: block; text-decoration: none; color: inherit;">
                                                Subcategoría
                                                @if(request('sort') === 'subcategoria')
                                                    @if(request('order') === 'asc')
                                                        <i class="fas fa-arrow-up"></i>
                                                    @else
                                                        <i class="fas fa-arrow-down"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-arrows-alt-v"></i> <!-- Flechas verticales -->
                                                @endif
                                            </a>
                                        </th>
                                        <th style="text-align:center; width: 23%;">
                                            <a href="{{ route('parametros.index', ['sort' => 'linea', 'order' => request('order') === 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'perPage' => request('perPage')]) }}" style="display: block; text-decoration: none; color: inherit;">
                                                Línea
                                                @if(request('sort') === 'linea')
                                                    @if(request('order') === 'asc')
                                                        <i class="fas fa-arrow-up"></i>
                                                    @else
                                                        <i class="fas fa-arrow-down"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-arrows-alt-v"></i> <!-- Flechas verticales -->
                                                @endif
                                            </a>
                                        </th>
                                        <th style="text-align:center; width: 23%;">
                                            <a href="{{ route('parametros.index', ['sort' => 'sublinea', 'order' => request('order') === 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'perPage' => request('perPage')]) }}" style="display: block; text-decoration: none; color: inherit;">
                                                Sublínea
                                                @if(request('sort') === 'sublinea')
                                                    @if(request('order') === 'asc')
                                                        <i class="fas fa-arrow-up"></i>
                                                    @else
                                                        <i class="fas fa-arrow-down"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-arrows-alt-v"></i> <!-- Flechas verticales -->
                                                @endif
                                            </a>
                                        </th>
                                        <th style="text-align:center; width: 8%;">Acciones</th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach($paginatedItems as $item)
                                        <tr>
                                            <td style="text-align:center;">{{ html_entity_decode($item['categoria']) }}</td>
                                            <td style="text-align:center;">{{ html_entity_decode($item['subcategoria']) }}</td>
                                            <td style="text-align:center;">{{ html_entity_decode($item['linea']) }}</td>
                                            <td style="text-align:center;">{{ html_entity_decode($item['sublinea']) }}</td>
                                            <td class="d-flex justify-content-center" style="gap: 4px;">
                                                <div class="btn-group d-flex justify-content-center" style="gap: 3px;">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                        style="background-color: #cc6633; color: white; height: 38px; display: flex; align-items: center; justify-content: center;">
                                                        Acciones
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                         <a class="dropdown-item" href="{{ route('categoria.edit', $item['categoria_id']) }}">
                                                             <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                                         </a>
                                                         <form action="{{ route('categoria.destroy', $item['categoria_id']) }}" method="POST" class="delete-form">
                                                             @csrf
                                                             @method('DELETE')
                                                             <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar categoria" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                                                <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                                            </button>
                                                         </form>
                                                         
                                                       
                                                            
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                                     
                                               
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        <div class="d-flex justify-content-center mt-4">
            {{ $paginatedItems->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
                        </div>
                     @endif
                </div>
                
                <!-- Encabezado -->
                

                {{-- 
                <!-- Sección: Categorías, Subcategorías, Líneas y Sublíneas -->
                
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">Categorías, Subcategorías, Líneas y Sublíneas para equipos</h5>
                    </div>
                    <div class="card-body">

                        <!-- Categorías -->
                        

                        <!-- Subcategorías -->
                        <!-- include('parametros._partials.subcategorias') -->

                        <!-- Líneas -->
                        <!-- include('parametros._partials.lineas') -->

                        <!-- Sublíneas -->
                        <!-- include('parametros._partials.sublineas') -->
                --}}
                <!-- Sección: Marcas -->
                    <div class="card mb-4">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0">Otros parametros</h5>
                        </div>
                        <div class="card-body">
                        <!-- Marcas -->
                        @include('parametros._partials.marcas')

                        <!-- Modelos -->
                        @include('parametros._partials.modelos')


                        <!-- Tipos de Visita -->
                        @include('parametros._partials.tiposVisita')

                        <!-- Tipos de Servicio -->
                        @include('parametros._partials.tipoServicios')


                        <!-- Usuarios -->
                        @include('parametros._partials.usuarios')

                        <!-- Técnicos -->
                        @include('parametros._partials.tecnicos')

                        <!-- Clientes -->
                        @include('parametros._partials.clientes')

                        <!-- Contactos -->
                        @include('parametros._partials.contactos')

                        <!-- Sucursales -->
                        @include('parametros._partials.sucursales')

                        <!-- Servicios -->
                        @include('parametros._partials.servicios')

                        {{-- <!-- Técnico-Servicios -->
                        @include('parametros._partials.tecnicoServicios') --}}

                        <!-- Tareas -->
                        @include('parametros._partials.tareas')

                        <!-- Dispositivos -->
                        @include('parametros._partials.dispositivos')

                        <!-- Estados de OT -->
                        @include('parametros._partials.estadosOt')

                        {{-- <!-- Dispositivos OT -->
                        @include('parametros._partials.dispositivosOt')

                        <!-- Tareas OT -->
                        @include('parametros._partials.tareasOt') --}}

                        {{-- <!-- Contactos OT -->
                        @include('parametros._partials.contactosOt') --}}

                        {{-- <!-- Equipos Técnicos -->
                        @include('parametros._partials.equiposTecnicos') --}}

                    </div>
                </div>
     </div> 
</main>

@endsection

@section('scripts')
<script>
        function saveScrollPosition() {
            localStorage.setItem('scrollPosition', window.scrollY);
        }
        
        document.getElementById('filterForm').addEventListener('submit', function() {
            localStorage.setItem('scrollPosition', window.scrollY);
        });
        window.onload = function() {
            const scrollPosition = localStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
                localStorage.removeItem('scrollPosition'); // Limpiar la posición después de usarla
            }
        };
    </script>
    <!-- Scripts de SweetAlert para Creaciones -->
    @if(session('categoria_nombre'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Categoría Creada',
                text: "La categoría '{{ session('categoria_nombre') }}' ha sido creada correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if(session('subcategoria_nombre') && session('categoria_nombre'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Subcategoría Creada',
                text: "La subcategoría '{{ session('subcategoria_nombre') }}' ha sido creada y asignada a la categoría '{{ session('categoria_nombre') }}' correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if(session('linea_nombre') && session('subcategoria_nombre'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Línea Creada',
                text: "La línea '{{ session('linea_nombre') }}' ha sido creada y asignada a la subcategoría '{{ session('subcategoria_nombre') }}' correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if(session('sublinea_nombre') && session('linea_nombre'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sublínea Creada',
                text: "La sublínea '{{ session('sublinea_nombre') }}' ha sido creada y asignada a la línea '{{ session('linea_nombre') }}' correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    <!-- Scripts de SweetAlert para Eliminaciones -->
    @if(session('categoria_deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Categoría Eliminada',
                text: "La categoría '{{ session('categoria_deleted') }}' ha sido eliminada correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if(session('subcategoria_deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Subcategoría Eliminada',
                text: "La subcategoría '{{ session('subcategoria_deleted') }}' ha sido eliminada correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if(session('linea_deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Línea Eliminada',
                text: "La línea '{{ session('linea_deleted') }}' ha sido eliminada correctamente.",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if(session('delete_error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('delete_error') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('success') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    <!-- Script para Confirmación de Eliminación -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Confirmación de Eliminación
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault(); // Prevenir el envío inmediato del formulario
            let form = this; // Referencia al formulario actual

            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#cc6633', // Color del botón de confirmar
                cancelButtonColor: '#d33', // Color del botón de cancelar
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Enviar el formulario si se confirma
                }
            });

            // Forzar estilos de los botones después de que se muestre el modal
            Swal.getCancelButton().style.backgroundColor = '#d33'; // Color del botón de cancelar
            Swal.getConfirmButton().style.backgroundColor = '#cc6633'; // Color del botón de confirmar
        });
    });
</script>
@endsection
