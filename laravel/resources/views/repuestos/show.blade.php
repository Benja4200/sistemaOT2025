@extends('layouts.master')

@section('content')
<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mt-3">
                <h2>Detalles del Repuesto</h2>
                <a href="{{ route('repuestos.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                    <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                </a>
            </div>
    
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">Id</th>
                        <th onclick="sortTable(1)">Nombre Repuesto</th>
                        <th onclick="sortTable(2)">descripcion repuesto</th>
                        <th onclick="sortTable(3)">parte numero repuesto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $repuestozzz->id }}</td>
                        <td>{{ $repuestozzz->nombre_repuesto }}</td>
                        <td>{{ $repuestozzz->descripcion_repuesto }}</td>
                        <td>{{ $repuestozzz->part_number_repuesto }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="card m-3">
                <div class="card-header">
                    Modelos Relacionados
                </div>
                <div class="card-body">
                    @if($repuestozzz->modelos->isEmpty())
                        <p>No tiene modelos asignados.</p>
                    @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Número de Parte</th>
                                <th>Nombre Modelo</th>
                                <th>Descripción Corta Modelo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($repuestozzz->modelos as $modelo)
                            <tr>
                                <td>{{ $modelo->part_number_modelo }}</td>
                                <td>{{ $modelo->nombre_modelo  }}</td>
                                <td>{{ $modelo->desc_corta_modelo }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
             
            {{--       
            <a href="{{ route('repuestos.index') }}" style="background-color: #cc6633; border-color: #cc6633;" class="btn btn-secondary ml-2">Volver a la lista</a>
            --}}
        
    </div>
</main>
@endsection
