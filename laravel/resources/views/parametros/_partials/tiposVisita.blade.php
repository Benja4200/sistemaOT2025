<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Tipos de Visita</summary>
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
                @foreach ($tipos_visita as $tipo_visita)
                <tr>
                    <td>{{ $tipo_visita->id }}</td>
                    <td>{{ $tipo_visita->descripcion_tipo_visita }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
