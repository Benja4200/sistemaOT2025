<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Usuarios</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Email Verificado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nombre_usuario }}</td>
                    <td>{{ $usuario->email_usuario }}</td>
                    <td>{{ $usuario->rol_usuario }}</td>
                    <td>{{ $usuario->email_verified_at ? $usuario->email_verified_at->format('Y-m-d H:i:s') : 'No' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
