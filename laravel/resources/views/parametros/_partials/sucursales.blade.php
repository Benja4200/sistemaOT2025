<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Sucursales</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Cliente</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sucursales as $sucursal)
                <tr>
                    <td>{{ $sucursal->id }}</td>
                    <td>{{ $sucursal->nombre_sucursal }}</td>
                    <td>{{ $sucursal->telefono_sucursal }}</td>
                    <td>{{ $sucursal->direccion_sucursal }}</td>
                    <td>{{ $sucursal->cliente->nombre_cliente ?? 'No asignado' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
