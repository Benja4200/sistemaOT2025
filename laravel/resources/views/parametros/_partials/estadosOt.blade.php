<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Estados de OT</summary>
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
                @foreach ($estados_ot as $estado_ot)
                <tr>
                    <td>{{ $estado_ot->id }}</td>
                    <td>{{ $estado_ot->descripcion_estado_ot }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
