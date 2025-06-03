@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2>Detalle del Modelo</h2>
            <a href="{{ route('modelos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
            </a>
        </div>

        <!-- Detalle del Modelo -->
        <div class="card mt-3">
            <div class="card-header">
                Detalle del Modelo
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ID del Modelo</td>
                            <td>{{ $modelo->id }}</td>
                        </tr>
                        <tr>
                            <td>Categoría</td>
                            <td>{{ $modelo->sublinea->linea->subcategoria->categoria->nombre_categoria ?? 'No asignada' }}</td>
                        </tr>
                        <tr>
                            <td>Subcategoría</td>
                            <td>{{ $modelo->sublinea->linea->subcategoria->nombre_subcategoria ?? 'No asignada' }}</td>
                        </tr>
                        <tr>
                            <td>Línea</td>
                            <td>{{ $modelo->sublinea->linea->nombre_linea ?? 'No asignada' }}</td>
                        </tr>
                        <tr>
                            <td>Sublinea</td>
                            <td>{{ $modelo->sublinea->nombre_sublinea ?? 'No asignada' }}</td>
                        </tr>
                        <tr>
                            <td>Marca</td>
                            <td>{{ $modelo->marca->nombre_marca ?? 'No asignada' }}</td>
                        </tr>
                        <tr>
                            <td>Nombre del Modelo</td>
                            <td>{{ $modelo->nombre_modelo }}</td>
                        </tr>
                        <tr>
                            <td>Part Number</td>
                            <td>{{ $modelo->part_number_modelo }}</td>
                        </tr>
                        <tr>
                            <td>Descripción Corta</td>
                            <td>{{ $modelo->desc_corta_modelo }}</td>
                        </tr>
                        <tr>
                            <td>Descripción Larga</td>
                            <td>{{ $modelo->desc_larga_modelo }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Dispositivos Relacionados -->
                <div class="card mt-3">
                    <div class="card-header">
                        Dispositivos Relacionados
                    </div>
                    <div class="card-body">
                        @if ($modelo->dispositivos->isEmpty())
                        <p>No hay dispositivos relacionados con este modelo.</p>
                        @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Número de Serie</th>
                                    <th>Sucursal</th>
                                    <th>Teléfono Sucursal</th>
                                    <th>Dirección Sucursal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modelo->dispositivos as $dispositivo)
                                <tr>
                                    <td>{{ $dispositivo->numero_serie_dispositivo }}</td>
                                    <td>{{ $dispositivo->sucursal->nombre_sucursal ?? 'No asignada' }}</td>
                                    <td>{{ $dispositivo->sucursal->telefono_sucursal ?? 'No disponible' }}</td>
                                    <td>{{ $dispositivo->sucursal->direccion_sucursal ?? 'No disponible' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        Repuestos Relacionados
                    </div>
                    <div class="card-body">
                        @if ($repuestosAsignados->isEmpty())
                        <p>No hay repuestos relacionados con este modelo.</p>
                        @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Número de Parte</th>
                                    <th>Nombre Repuesto</th>
                                    <th>Descripción Repuesto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($repuestosAsignados as $repuesto)
                                <tr>
                                    <td>{{ $repuesto->part_number_repuesto }}</td>
                                    <td>{{ $repuesto->nombre_repuesto  }}</td>
                                    <td>{{ $repuesto->descripcion_repuesto  }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
                
                {{--
                <a href="{{ route('modelos.index') }}" class="btn btn-primary" style="background-color: #cc6633; border-color: #cc6633;">
                    Volver
                </a>
                --}}
            </div>
        </div>
    </div>
</main>
@endsection
