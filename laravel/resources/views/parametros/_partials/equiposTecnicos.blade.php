<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Equipos Técnicos</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Técnico</th>
                    <th>OT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($equipos_tecnicos as $equipo_tecnico)
                <tr>
                    <td>{{ $equipo_tecnico->id }}</td>
                    <td>{{ $equipo_tecnico->tecnico->nombre_tecnico ?? 'No asignado' }}
                    </td>
                    <td>{{ $equipo_tecnico->ot->numero_ot ?? 'No asignado' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
