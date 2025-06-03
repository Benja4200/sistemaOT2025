@extends('impresiones.partials.imp-layout')
@section('title', 'Orden de Trabajo')
@section('informe-titulo', 'Orden de Trabajo '.$informe->numero_ot)

@section('content')
<div class="p-2 m-2" style="background-color: #f2f2f2; font-size:12px;border: 1px solid black;border-radius:5px">
    <h1>Requerimiento</h1>
    <p>Requerimiento: {{ $informe->descripcion_ot ?? 'n/a' }}</p>
</div>

@if (isset($grupoTareas))
    <div class="m-2">
        <h2>Tareas</h2>
    </div>
    <div class="m-2" style="text-align: center;border: 1px solid black;border-radius:5px">
        <ul style="list-style-type: disc; padding-left: 0; display: inline-block; text-align: left;">
            @foreach ($grupoTareas as $tarea)
                <li>{{ html_entity_decode($tarea->tarea->nombre_tarea ?? 'n/a') }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (isset($grupoDispositivos))
    <div class="m-2">
        <h2>Dispositivos</h2>
    </div>
        
    @foreach ($grupoDispositivos as $dispositivo)
    <div class="dispositivos m-2" style="text-align: center;border: 1px solid black;border-radius:5px">
        <table class="table table-bordered tablea2" style="width: 80%; margin: auto;">
            <thead>
                <tr>
                    <th>Número de Serie</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $dispositivo->dispositivo->numero_serie_dispositivo ?? 'n/a' }}</td>
                    <td>{{ $dispositivo->dispositivo->modelo->nombre_modelo ?? 'n/a' }}</td>
                    <td>{{ $dispositivo->dispositivo->modelo->marca->nombre_marca ?? 'n/a' }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="mt-2" style="text-align: center;">
            <table class="table table-bordered tablea2" style="width: 40%; margin: auto;">
                <thead>
                    <tr>
                        <th>Tarea(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dispositivo->tareaDispositivo as $tarea)
                    <tr>
                        <td>{{ html_entity_decode($tarea->tarea->nombre_tarea ?? 'n/a') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>   
    </div>
    @endforeach
@endif

<div class="p-2 mx-2" style="background-color: #f2f2f2; font-size:12px;border: 1px solid black;border-radius:5px">
    <h1>Cierre de Orden</h1>
    <p>{{ html_entity_decode($informe->comentario_ot ?? 'n/a') }}</p>
</div>
@endsection