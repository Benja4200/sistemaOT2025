<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Contactos</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Departamento</th>
                    <th>Cargo</th>
                    <th>Email</th>
                    <th>Sucursal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contactos as $contacto)
                <tr>
                    <td>{{ $contacto->id }}</td>
                    <td>{{ $contacto->nombre_contacto }}</td>
                    <td>{{ $contacto->telefono_contacto }}</td>
                    <td>{{ $contacto->departamento_contacto }}</td>
                    <td>{{ $contacto->cargo_contacto }}</td>
                    <td>{{ $contacto->email_contacto }}</td>
                    <td>{{ $contacto->sucursal->nombre_sucursal ?? 'No asignada' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
