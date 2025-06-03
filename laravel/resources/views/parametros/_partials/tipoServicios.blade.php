<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Tipos de Servicio</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tipos_servicio as $tipo_servicio)
                <tr>
                    <td>{{ $tipo_servicio->id }}</td>
                    <td>{{ $tipo_servicio->descripcion_tipo_servicio }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
