@extends('layouts.master')

@section('content')

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Detalle del Técnico -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        
                        <style>
                            .imghoverx {
                              width: 32px;
                            }
                        
                            .imghoverx:hover {
                              opacity: 0.5;
                            }
                      </style>
                        
                        
                        
                        <h2>Detalle del Técnico</h2>
                        <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                            <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                        </a>
                    </div>

                    <!-- Información del Técnico -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Información del Técnico
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>RUT</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Precio por Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $tecnico->nombre_tecnico }}</td>
                                        <td>{{ $tecnico->rut_tecnico }}</td>
                                        <td>{{ $tecnico->telefono_tecnico }}</td>
                                        <td>{{ $tecnico->email_tecnico }}</td>
                                        <td>${{ number_format($tecnico->precio_hora_tecnico, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Información del Usuario Asociado -->
                    @if ($tecnico->usuario)
                        <div class="card mt-3">
                            <div class="card-header">
                                Información del Usuario Asociado
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre de Usuario</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $tecnico->usuario->nombre_usuario }}</td>
                                            <td>{{ $tecnico->usuario->email_usuario }}</td>
                                            @foreach ($roles as $role)
                                                <td>
                                                    <span class="badge" style="background-color: {{ $role->color }}; color: white;">{{ $role->name }}</span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="card mt-3">
                            <div class="card-header">
                                Información del Usuario Asociado
                            </div>
                            <div class="card-body">
                                <p>No hay usuario asociado.</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="card mt-3">
                        
                        <div class="card-header">
                            Servicios asociados al tecnico
                        </div>

                        <ul class="list-group m-4">
                            @foreach($filtropatipo_servicio as $servicio)
                                <li class="list-group-item">
                                    {{ $servicio->servicio->nombre_servicio }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
