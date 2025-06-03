<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Modelos</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripción Corta</th>
                    <th>Descripción Larga</th>
                    <th>Part Number</th>
                    <th>Marca</th>
                    <th>Sublínea</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modelos as $modelo)
                <tr>
                    <td>{{ $modelo->id }}</td>
                    <td>{{ $modelo->nombre_modelo }}</td>
                    <td>{{ $modelo->desc_corta_modelo }}</td>
                    <td>{{ $modelo->desc_larga_modelo }}</td>
                    <td>{{ $modelo->part_number_modelo }}</td>
                    <td>{{ $modelo->marca->nombre_marca ?? 'No asignada' }}</td>
                    <td>{{ $modelo->sublinea->nombre_sublinea ?? 'No asignada' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
