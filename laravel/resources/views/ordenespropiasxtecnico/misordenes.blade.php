@extends('layouts.master')

@section('content')
<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">

  {{-- Header: título, exportar y filtros --}}
  <div class="container-fluid mb-3 px-3">
      @if($notificaciones->isNotEmpty())
        <div class="alert alert-info">
            <h4>Órdenes pendientes asignadas:</h4>
            <ul id="notificationList">
                @foreach($notificaciones as $notification)
                    <li>
                        {{ $notification->data['mensaje'] }}
                        <a href="{{ route('editor_avance',$notification->data['orden_id']) }}" class="btn btn-primary btn-sm">Iniciar orden</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-success">
            <p>No tienes órdenes pendientes por iniciar.</p>
        </div>
    @endif
    <div class="row align-items-center gy-2">
      {{-- Título + Excel --}}
      <div class="col-md-4 d-flex align-items-center gap-2">
        <h2 class="mb-0">Órdenes <small class="text-muted">({{ $cantidadOrdenes }})</small></h2>
        {{-- <img src="{{ asset('assets/image/iconoexportexcel.png') }}"
             alt="Exportar a Excel"
             class="img-fluid"
             style="width: 32px; cursor: pointer;"
             onclick="exportToExcel()"> --}}
      </div>
      {{-- Búsqueda --}}
      <div class="col-md-4">
        <form method="GET" action="{{ route('buscarOrdenes') }}" class="d-flex" style="gap: 8px;">
          <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar OT..." style="border-color: #cc6633;">
          <button type="submit" class="btn" style="background-color: #cc6633; color: #fff;"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
      </div>
      {{-- Eliminar filtro --}}
      <div class="col-md-4 text-md-end">
        <a href="{{ route('misOrdenes') }}" class="btn" style="background-color: #cc6633; color: #fff;"><i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro</a>
      </div>
    </div>
  </div>

  {{-- Tabla responsiva --}}
  
  <div class="table-responsive mt-3" style="min-height:300px">
    <table class="table table-striped" id="misordenes_table">
      <thead>
        <tr>
          <th onclick="sortTable(0)" class="text-center" style="cursor: pointer;"># OT</th>
          <th onclick="sortTable(1)" style="cursor: pointer;">Cliente</th>
          <th onclick="sortTable(2)" style="cursor: pointer;">Sucursal</th>
          <th onclick="sortTable(3)" style="cursor: pointer;">Servicio</th>
          <th onclick="sortTable(4)" style="cursor: pointer;">Responsable</th>
          {{-- <th onclick="sortTable(4)" style="cursor: pointer;">Contacto</th> --}}
          <th onclick="sortTable(5)" class="text-center" style="cursor: pointer;">Estado</th>
          <th onclick="sortTable(6)" class="text-center" style="cursor: pointer;">Fecha</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @if(!empty($noResultados) && $noResultados)
          <tr><td colspan="8" class="text-center py-4"><div class="alert alert-warning mb-0"><strong>No se encontraron resultados.</strong></div></td></tr>
        @endif
        @foreach($datosMisOrdenes as $orden)
          @php
            $dispList = $orden->DispositivoOT ?? collect();
            if ($dispList->isNotEmpty()) {
                $tooltip = $dispList->map(function($dispOt) {
                    $modelo = optional(optional($dispOt->dispositivo)->modelo)->nombre_modelo ?? 'Modelo N/D';
                    $tasks = html_entity_decode(\App\Models\TareaDispositivo::where('cod_dispositivo_ot',$dispOt->id)->with('tarea')->get()->pluck('tarea.nombre_tarea')->join(', '));
                    return "{$modelo}: {$tasks}";
                })->join(' | ');
            } else {
                $taskList = $orden->TareasOt ?? collect();
                $tooltip = html_entity_decode($taskList->pluck('tarea.nombre_tarea')->join(', '));
            }
          @endphp
          <tr data-toggle="tooltip" data-placement="top" title="{{ $tooltip ?: 'Sin tareas' }}">
            <td class="text-center font-weight-bold">{{ $orden->numero_ot }}</td>
            <td>{{ $orden->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente }}</td>
            <td>{{ $orden->contactoOt[0]->contacto->sucursal->nombre_sucursal }}</td>
            <td>{{ $orden->servicio->nombre_servicio }}</td>
            <td>{{ $orden->tecnicoEncargado->nombre_tecnico }}</td>
            {{-- <td><span data-toggle="tooltip" data-placement="top" title="{{ $orden->contacto->nombre_contacto }}">{{ \Illuminate\Support\Str::limit($orden->contacto->nombre_contacto,15,'...') }}</span></td> --}}
             <td class="px-4 py-2">
                            @if ($orden->estado->descripcion_estado_ot == 'Iniciada')
                                <p style="background-color: green; color: white; padding: 5px; border-radius: 5px; text-align: center;">{{ $orden->estado->descripcion_estado_ot }}</p>
                            @elseif ($orden->estado->descripcion_estado_ot == 'Pendiente')
                                <p style="background-color: yellow; color: black; padding: 5px; border-radius: 5px; text-align: center;">{{ $orden->estado->descripcion_estado_ot }}</p>
                            @elseif ($orden->estado->descripcion_estado_ot == 'Finalizada')
                                <p style="background-color: red; color: white; padding: 5px; border-radius: 5px; text-align: center;">{{ $orden->estado->descripcion_estado_ot }}</p>
                            @elseif ($orden->estado->descripcion_estado_ot == 'Creada')
                                <p style="background-color: gray; color: white; padding: 5px; border-radius: 5px; text-align: center;">{{ $orden->estado->descripcion_estado_ot }}</p>
                            @endif
                        </td>

            <td class="text-center">{{ $orden->created_at->format('d/m/Y') }}</td>
            <td class="text-center">
              <div class="dropdown">
                <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#cc6633;color:#fff;">Acciones</button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('detalleOrdenTecnicoz',$orden->numero_ot) }}"><img src="{{ asset('assets/image/archivos-de-vista.png') }}" style="width:20px"> Detalles</a>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('editor_avance',$orden->numero_ot) }}"><i class="fas fa-plus text-success"></i> Avances</a>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('actividad_extra.create',['numero_ot'=>$orden->numero_ot]) }}"><span class="text-primary font-weight-bold">+1</span> Actividad Extra</a>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('imprimirOt', [$orden->numero_ot, 'Sinfirma']) }}" target="_blank" ><img src="{{ asset('assets/image/pdf.png') }}" style="width:20px"> PDF</a>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('vistaFirmaCliente', $orden->numero_ot) }}" target="_blank" ><img src="{{ asset('assets/image/pdf.png') }}" style="width:20px"> PDF con firma de cliente</a>

                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

            {{-- Paginación --}}
        <div class="pagination-scroll-container"> {{-- Nuevo contenedor para el scroll --}}
          {{ $datosMisOrdenes->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>

  {{-- Script exportar y tooltips --}}
  <script>
    $(function(){$('[data-toggle="tooltip"]').tooltip();});
    function exportToExcel(){
      const encabezados=["Número OT","Descripción","Fecha Plan.","Estado","Técnico"];
      const datos=@json($paraExcel);
      const rows=[encabezados,...datos.map(o=>[o.numero_ot,o.descripcion_ot,o.fecha_inicio_planificada_ot,o.cod_estado_ot,o.cod_tecnico_encargado])];
      const ws=XLSX.utils.aoa_to_sheet(rows);
      const wb=XLSX.utils.book_new();XLSX.utils.book_append_sheet(wb,ws,"Órdenes");XLSX.writeFile(wb,"ordenes.xlsx");
    }

    // Función para ordenar la tabla
    function sortTable(colIndex) {
      const table = document.getElementById('misordenes_table');
      const tbody = table.tBodies[0];
      const rows = Array.from(tbody.querySelectorAll('tr'));
      const isNumeric = (s) => !isNaN(parseFloat(s)) && isFinite(s);

      // Determinar orden actual o alternar asc/desc
      if (!table.sortDir) table.sortDir = 'asc';
      table.sortDir = table.sortDir === 'asc' ? 'desc' : 'asc';

      rows.sort((a, b) => {
        let aText = a.cells[colIndex].textContent.trim();
        let bText = b.cells[colIndex].textContent.trim();

        if (isNumeric(aText) && isNumeric(bText)) {
          return table.sortDir === 'asc'
            ? parseFloat(aText) - parseFloat(bText)
            : parseFloat(bText) - parseFloat(aText);
        }
        aText = aText.toLowerCase(); bText = bText.toLowerCase();
        if (aText < bText) return table.sortDir === 'asc' ? -1 : 1;
        if (aText > bText) return table.sortDir === 'asc' ? 1 : -1;
        return 0;
      });

      // Re-ordenar el DOM
      rows.forEach(row => tbody.appendChild(row));
    }
  </script>
</main>
@endsection