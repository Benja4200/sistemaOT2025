@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">

    <div class="col">
        <!-- Encabezado y Búsqueda -->
         
        <!-- Filtros -->
        <div class="d-flex flex-column align-items-start">

            <h2 class="ml-1">Filtrar Modelos de equipos</h2>

            {{-- Formulario de Filtros (Categoría, Subcategoría, etc.) --}}
            {{-- Este formulario enviará a 'modelos.index' porque es el que maneja los filtros --}}
            <form id="filter-form" action="{{ route('modelos.index') }}" method="get"
                class="row g-2 m-0 py-2 w-100"> {{-- Ajustado a w-100 para que ocupe todo el ancho --}}

                <div class="col-md-4 mb-2">
                    <select name="categoria" class="form-control form-control-sm w-100" onchange="this.form.submit()" style="border-color: #cc6633; margin-right: 4px;">
                        <option value="">Seleccionar Categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre_categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-2">
                    <select name="subcategoria" class="form-control form-control-sm w-100"
                        onchange="this.form.submit()" style="border-color: #cc6633; margin-right: 4px;">
                        <option value="">Seleccionar Subcategoría</option>
                        @foreach ($subcategorias as $subcategoria)
                            <option value="{{ $subcategoria->id }}" {{ request('subcategoria') == $subcategoria->id ? 'selected' : '' }}>
                                {{ $subcategoria->nombre_subcategoria }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-2">
                    <select name="linea" class="form-control form-control-sm w-100" onchange="this.form.submit()" style="border-color: #cc6633; margin-right: 4px;">
                        <option value="">Seleccionar Línea</option>
                        @foreach ($lineas as $linea)
                            <option value="{{ $linea->id }}" {{ request('linea') == $linea->id ? 'selected' : '' }}>
                                {{ $linea->nombre_linea }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-2">
                    <select name="sublinea" class="form-control form-control-sm w-100" onchange="this.form.submit()" style="border-color: #cc6633; margin-right: 4px;">
                        <option value="">Seleccionar Sublínea</option>
                        @foreach ($sublineas as $sublinea)
                            <option value="{{ $sublinea->id }}" {{ request('sublinea') == $sublinea->id ? 'selected' : '' }}>
                                {{ $sublinea->nombre_sublinea }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-2">
                    <select name="marca" class="form-control form-control-sm w-100" onchange="this.form.submit()" style="border-color: #cc6633; margin-right: 4px;">
                        <option value="">Seleccionar Marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->id }}" {{ request('marca') == $marca->id ? 'selected' : '' }}>
                                {{ $marca->nombre_marca }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Inputs ocultos para mantener 'search' y 'perPage' si existen --}}
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('perPage'))
                    <input type="hidden" name="perPage" value="{{ request('perPage') }}">
                @endif

            </form>
        </div>

        {{-- INICIO: Formulario de Búsqueda de Texto --}}
        {{-- Este formulario apunta al método 'search' para búsquedas por nombre/part number --}}
        <form action="{{ route('modelos.index') }}" method="get" class="d-flex mt-3" style="gap: 4px;">
            <input type="text" name="search" id="search" class="form-control" 
                placeholder="Buscar por ID, nombre, part number o descripción" 
                value="{{ request('search') }}" 
                style="border-color: #cc6633; margin-right: 4px;">

            <button type="submit" class="btn btn-primary d-flex align-items-center"
                style="background-color: #cc6633; border-color: #cc6633; gap: 4px;">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar 
            </button>
            {{-- Inputs ocultos para mantener los filtros de select al buscar por texto --}}
            @if(request('categoria'))
                <input type="hidden" name="categoria" value="{{ request('categoria') }}">
            @endif
            @if(request('subcategoria'))
                <input type="hidden" name="subcategoria" value="{{ request('subcategoria') }}">
            @endif
            @if(request('linea'))
                <input type="hidden" name="linea" value="{{ request('linea') }}">
            @endif
            @if(request('sublinea'))
                <input type="hidden" name="sublinea" value="{{ request('sublinea') }}">
            @endif
            @if(request('marca'))
                <input type="hidden" name="marca" value="{{ request('marca') }}">
            @endif
            {{-- Input oculto para mantener el número de registros por página al buscar --}}
            @if(request('perPage'))
                <input type="hidden" name="perPage" value="{{ request('perPage') }}">
            @endif
        </form>
        {{-- FIN: Formulario de Búsqueda de Texto --}}


        {{-- INICIO: Formulario para el número de registros por página --}}
        <div class="d-flex justify-content-start align-items-center mt-3">
            {{-- La acción del formulario de perPage debe considerar si hay búsqueda de texto o filtros activos --}}
            <form action="{{ request('search') || request('categoria') || request('subcategoria') || request('linea') || request('sublinea') || request('marca') ? route('modelos.index') : route('modelos.index') }}" 
                  method="get" id="modelosPerPageForm" class="d-flex align-items-center">
                <div class="form-group d-flex align-items-center me-3">
                    <label for="perPage" class="me-2 mb-0 text-nowrap">Mostrar </label>
                    <input type="number" name="perPage" id="perPage"
                           class="form-control form-control-sm text-center"
                           value="{{ request()->input('perPage', 10) }}" min="1" style="width: 70px;"
                           onchange="this.form.submit()">
                    <label class="ms-2 mb-0 text-nowrap"> registros </label>
                </div>
                {{-- Inputs ocultos para mantener todos los parámetros de la URL al cambiar perPage --}}
                @foreach(request()->except(['perPage', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
            </form>
        </div>
        {{-- FIN: Formulario para el número de registros por página --}}
        <!-- Botones de Agregar y Eliminar Filtro -->
        <div class="d-flex align-items-center justify-content-end mt-1 py-2 pr-1" style="gap: 1rem;">
            <a href="{{ route('modelos.create') }}" class="btn btn-secondary btn-sm"
                style="background-color: #cc6633; border-color: #cc6633;">
                <i class="fa fa-plus-circle"></i> Agregar
            </a>
            <a href="{{ route('modelos.index') }}" class="btn btn-secondary btn-sm"
                style="background-color: #cc6633; border-color: #cc6633;">
                <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
            </a>
        </div>

        <!-- Tabla de Modelos -->
        <table class="table table-responsive table-striped sortable-table" id="modelos_tabledata">
            <thead>
                <tr>
                    <th onclick="sortTable(this,0)">Categoría</th>
                    <th onclick="sortTable(this,1)">Subcategoría</th>
                    <th onclick="sortTable(this,2)">Línea</th>
                    <th onclick="sortTable(this,3)">Sublinea</th>
                    <th onclick="sortTable(this,4)">Marca</th>
                    <th onclick="sortTable(this,5)">Id</th>
                    <th onclick="sortTable(this,6)">Nombre Modelo</th>
                    <th onclick="sortTable(this,7)">Part Number</th>
                    <th onclick="sortTable(this,8)">Descripción Corta</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($modelos as $modelo)
                    <tr>
                        <td>{{ optional($modelo->sublinea)->linea ? optional($modelo->sublinea->linea->subcategoria)->categoria->nombre_categoria ?? 'N/A' : 'N/A' }}</td>
                        <td>{{ optional($modelo->sublinea)->linea ? optional($modelo->sublinea->linea->subcategoria)->nombre_subcategoria ?? 'N/A' : 'N/A' }}</td>
                        <td>{{ optional($modelo->sublinea)->linea ? optional($modelo->sublinea->linea)->nombre_linea ?? 'N/A' : 'N/A' }}</td>
                        <td>{{ optional($modelo->sublinea)->nombre_sublinea ?? 'N/A' }}</td>
                        <td>{{ optional($modelo->marca)->nombre_marca ?? 'N/A' }}</td>
                        <td>{{ $modelo->id }}</td>
                        <td>{{ $modelo->nombre_modelo }}</td>
                        <td>{{ $modelo->part_number_modelo }}</td>
                        <td>{{ $modelo->desc_corta_modelo }}</td>
                        <td class="d-flex" style="gap: 4px;">
                            <div class="btn-group">
                                 <button type="button" class="btn dropdown-toggle"
                                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                     style="background-color: #cc6633; color: white; height: 38px; display: flex; align-items: center; justify-content: center;">
                                     Acciones
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
 
                                     <!-- Ver Modelo -->
                                     <a class="dropdown-item" href="{{ route('modelos.show', $modelo->id) }}">
                                         <i class="fas fa-eye" style="color: #cc0066;"></i> Ver Modelo
                                     </a>
 
                                     <!-- Asignar Repuestos -->
                                     <a class="dropdown-item" href="{{ route('modelos.asignar_repuestos', $modelo->id) }}">
                                         <i class="fas fa-tools" style="color: #6b0438;"></i> Asignar Repuestos
                                     </a>
 
                                     <!-- Editar Modelo -->
                                     <a class="dropdown-item" href="{{ route('modelos.edit', $modelo->id) }}">
                                         <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                     </a>
 
                                     <!-- Eliminar Modelo -->
                                     <form action="{{ route('modelos.destroy', $modelo->id) }}" method="POST" class="delete-form">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar modelo" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                            <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                        </button>
                                     </form>
                                 </div>
                             </div>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">No hay modelos para mostrar.</td>
                </tr>
                @endforelse
            </tbody>

        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $modelos->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        console.log("Se dispara");
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@endsection
