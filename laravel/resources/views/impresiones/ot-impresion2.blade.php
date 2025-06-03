@extends('impresiones.partials.imp-layout')
@section('title', 'Orden de Trabajo')
@section('informe-titulo', 'Orden de Trabajo '.$informe->numero_ot)

@section('content')
    <div class="m-2">
        <h2>Avances</h2>
    </div>
    <div class="m-2" style="border: 1px solid black; border-radius: 5px; padding: 10px;">
        @foreach($grupoAvances as $avance) <!-- Cambiar de $informe->avances a $grupoAvances -->
            <div style="border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; padding: 10px; position: relative;">
                <div style="position: absolute; top: 5px; right: 10px; font-size: 12px; color: black; margin-bottom: 5px;">
                    <strong>Fecha Avance: {{ $avance->fecha_avance }}</strong>
                </div>
                <div style="font-size: 14px; margin-top: 20px;">
                    {{ html_entity_decode($avance->comentario_avance) }}
                </div>
            </div>
        @endforeach
    </div>
@endsection