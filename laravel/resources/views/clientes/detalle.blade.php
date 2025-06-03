@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Detalle del Cliente -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Detalle del Cliente</h2>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <!-- Información del Cliente -->
                <div class="card mt-3">
                    <div class="card-header">
                        Información del Cliente
                    </div>
                    <div class="card-body table-responsive ">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>RUT</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Sitio Web</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $cliente->nombre_cliente ?? 'No disponible' }}</td>
                                    <td>{{ $cliente->rut_cliente ?? 'No disponible' }}</td>
                                    <td>{{ $cliente->email_cliente ?? 'No disponible' }}</td>
                                    <td>{{ $cliente->telefono_cliente ?? 'No disponible' }}</td>
                                    <td>{{ $cliente->web_cliente ?? 'No disponible' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Información de Sucursales Asociadas -->
                <div class="card mt-3">
                    <div class="card-header">
                        Sucursales Asociadas
                    </div>
                    <div class="card-body table-responsive ">
                        @if($cliente->sucursal->isEmpty())
                        <p>No hay sucursales asociadas.</p>
                        @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->sucursal as $sucursal)
                                <tr>
                                    <td>{{ $sucursal->nombre_sucursal ?? 'No disponible' }}</td>
                                    <td>{{ $sucursal->telefono_sucursal ?? 'No disponible' }}</td>
                                    <td>{{ $sucursal->direccion_sucursal ?? 'No disponible' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
