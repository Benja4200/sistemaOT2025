@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Detalle de la Sucursal -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Detalle de la Sucursal</h2>
                    <a href="{{ route('sucursales.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <!-- Información de la Sucursal -->
                <div class="card mt-3">
                    <div class="card-header">
                        Información de la Sucursal
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $sucursal->nombre_sucursal }}</td>
                                    <td>{{ $sucursal->telefono_sucursal }}</td>
                                    <td>{{ $sucursal->direccion_sucursal }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Información del Cliente Asociado -->
                @if($sucursal->cliente)
                <div class="card mt-3">
                    <div class="card-header">
                        Información del Cliente Asociado
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre Cliente</th>
                                    <th>RUT Cliente</th>
                                    <th>Teléfono Cliente</th>
                                    <th>Email Cliente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $sucursal->cliente->nombre_cliente }}</td>
                                    <td>{{ $sucursal->cliente->rut_cliente }}</td>
                                    <td>{{ $sucursal->cliente->telefono_cliente }}</td>
                                    <td>{{ $sucursal->cliente->email_cliente }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="card mt-3">
                    <div class="card-header">
                        Información del Cliente Asociado
                    </div>
                    <div class="card-body">
                        <p>No hay cliente asociado.</p>
                    </div>
                </div>
                @endif

                <!-- Contactos Asociados -->
                @if($sucursal->contacto->isEmpty())
                <div class="card mt-3">
                    <div class="card-header">
                        Contactos Asociados
                    </div>
                    <div class="card-body">
                        <p>No hay contactos asociados.</p>
                    </div>
                </div>
                @else
                <div class="card mt-3">
                    <div class="card-header">
                        Contactos Asociados
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Departamento</th>
                                    <th>Cargo</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sucursal->contacto as $contacto)
                                <tr>
                                    <td>{{ $contacto->nombre_contacto }}</td>
                                    <td>{{ $contacto->telefono_contacto }}</td>
                                    <td>{{ $contacto->departamento_contacto }}</td>
                                    <td>{{ $contacto->cargo_contacto }}</td>
                                    <td>{{ $contacto->email_contacto }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Dispositivos Asociados -->
                @if($sucursal->dispositivo->isEmpty())
                <div class="card mt-3">
                    <div class="card-header">
                        Dispositivos Asociados
                    </div>
                    <div class="card-body">
                        <p>No hay dispositivos asociados.</p>
                    </div>
                </div>
                @else
                <div class="card mt-3">
                    <div class="card-header">
                        Dispositivos Asociados
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Número de Serie</th>
                                    <th>Modelo</th>
                                    <th>Descripción Corta</th>
                                    <th>Descripción Larga</th>
                                    <th>Part Number</th>
                                    <th>Marca</th>
                                    <th>Sublinea</th>
                                    <th>Linea</th>
                                    <th>Subcategoría</th>
                                    <th>Categoría</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sucursal->dispositivo as $dispositivo)
                                <tr>
                                    <td>{{ $dispositivo->numero_serie_dispositivo }}</td>
                                    <td>{{ $dispositivo->modelo->nombre_modelo ?? 'No asignado' }}</td>
                                    <td>{{ $dispositivo->modelo->desc_corta_modelo ?? 'No disponible' }}</td>
                                    <td>{{ $dispositivo->modelo->desc_larga_modelo ?? 'No disponible' }}</td>
                                    <td>{{ $dispositivo->modelo->part_number_modelo ?? 'No disponible' }}</td>
                                    <td>{{ $dispositivo->modelo->marca->nombre_marca ?? 'No asignada' }}</td>
                                    <td>{{ $dispositivo->modelo->sublinea->nombre_sublinea ?? 'No asignada' }}</td>
                                    <td>{{ $dispositivo->modelo->sublinea->linea->nombre_linea ?? 'No asignada' }}</td>
                                    <td>{{ $dispositivo->modelo->sublinea->linea->subcategoria->nombre_subcategoria ?? 'No asignada' }}</td>
                                    <td>{{ $dispositivo->modelo->sublinea->linea->subcategoria->categoria->nombre_categoria ?? 'No asignada' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
