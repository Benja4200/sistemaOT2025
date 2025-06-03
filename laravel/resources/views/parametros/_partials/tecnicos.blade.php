<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Técnicos</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>RUT</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Precio por Hora</th>
                    <th>Usuario Asociado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tecnicos as $tecnico)
                <tr>
                    <td>{{ $tecnico->id }}</td>
                    <td>{{ $tecnico->nombre_tecnico }}</td>
                    <td>{{ $tecnico->rut_tecnico }}</td>
                    <td>{{ $tecnico->telefono_tecnico }}</td>
                    <td>{{ $tecnico->email_tecnico }}</td>
                    <td>${{ number_format($tecnico->precio_hora_tecnico) }}</td>
                    <td>{{ $tecnico->usuario->nombre_usuario ?? 'No asignado' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
