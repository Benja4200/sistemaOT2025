<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Servicios</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Tipo de Servicio</th>
                    <th>Sublínea</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->id }}</td>
                    <td>{{ $servicio->nombre_servicio }}</td>
                    <td>{{ $servicio->tipoServicio->descripcion_tipo_servicio ?? 'No asignado' }}
                    </td>
                    <td>{{ $servicio->sublinea->nombre_sublinea ?? 'No asignada' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
