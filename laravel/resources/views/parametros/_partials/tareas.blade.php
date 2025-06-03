<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Tareas</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Tiempo</th>
                    <th>Servicio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tareas as $tarea)
                <tr>
                    <td>{{ $tarea->id }}</td>
                    <td>{{ $tarea->nombre_tarea }}</td>
                    <td>{{ $tarea->tiempo_tarea }}</td>
                    <td>{{ $tarea->servicio->nombre_servicio ?? 'No asignado' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
