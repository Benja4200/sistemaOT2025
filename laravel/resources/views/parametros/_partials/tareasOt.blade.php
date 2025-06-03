<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Tareas OT</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Tarea</th>
                    <th>OT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tareas_ot as $tarea_ot)
                <tr>
                    <td>{{ $tarea_ot->id }}</td>
                    <td>{{ $tarea_ot->tarea->nombre_tarea ?? 'No asignada' }}</td>
                    <td>{{ $tarea_ot->ot->numero_ot ?? 'No asignada' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
