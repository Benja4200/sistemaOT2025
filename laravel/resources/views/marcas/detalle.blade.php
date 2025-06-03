@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- Detalle Marca -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Detalle de la Marca</h2>
                    <a href="{{ route('marcas.index') }}" class="btn btn-secondary"
                        style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        Información de la Marca
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de la Marca</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $marca->id }}</td>
                                    <td>{{ $marca->nombre_marca }}</td>
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
