<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Dispositivos OT</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Dispositivo</th>
                    <th>OT</th>
                    <th>Detalles</th>
                    <th>Accesorios</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dispositivos_ot as $dispositivo_ot)
                <tr>
                    <td>{{ $dispositivo_ot->id }}</td>
                    <td>{{ $dispositivo_ot->dispositivo->numero_serie_dispositivo ?? 'No asignado' }}
                    </td>
                    <td>{{ $dispositivo_ot->ot->numero_ot ?? 'No asignado' }}</td>
                    <td>{{ $dispositivo_ot->detalles ? 'Sí' : 'No' }}</td>
                    <td>{{ $dispositivo_ot->accesorios ? 'Sí' : 'No' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
