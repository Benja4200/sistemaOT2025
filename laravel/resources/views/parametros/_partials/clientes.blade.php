<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Clientes</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>RUT</th>
                    <th>Web</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ html_entity_decode($cliente->nombre_cliente) }}</td>
                    <td>{{ $cliente->rut_cliente }}</td>
                    <td>{{ $cliente->web_cliente }}</td>
                    <td>{{ $cliente->telefono_cliente }}</td>
                    <td>{{ $cliente->email_cliente }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
