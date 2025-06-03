@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Detalle del Dispositivo -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Detalle del Dispositivo</h2>
                    <a href="{{ route('dispositivos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <!-- Información del Dispositivo -->
                <div class="card mt-3">
                    <div class="card-header">
                        Información del Dispositivo
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Número de Serie</th>
                                    <th>Modelo</th>
                                    <th>Sucursal</th>
                                    <th>Creado en</th>
                                    <th>Actualizado en</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $dispositivo->numero_serie_dispositivo }}</td>
                                    <td>{{ $dispositivo->modelo->nombre_modelo }}</td>
                                    <td>{{ $dispositivo->sucursal->nombre_sucursal }}</td>
                                    <td>{{ $dispositivo->created_at }}</td>
                                    <td>{{ $dispositivo->updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
