<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Dispositivos</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Número de Serie</th>
                    <th>Modelo</th>
                    <th>Sucursal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dispositivos as $dispositivo)
                <tr>
                    <td>{{ $dispositivo->id }}</td>
                    <td>{{ $dispositivo->numero_serie_dispositivo }}</td>
                    <td>{{ $dispositivo->modelo->nombre_modelo ?? 'No asignado' }}</td>
                    <td>{{ $dispositivo->sucursal->nombre_sucursal ?? 'No asignada' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
