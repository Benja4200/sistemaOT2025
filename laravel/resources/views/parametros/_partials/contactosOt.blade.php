<details style="width: 100%;">
    <summary
        style="font-size: 1.25rem; padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; cursor: pointer; width: 100%;">
        Contactos OT</summary>
    <div class="table-responsive mt-3"
        style="max-height: 300px; overflow-y: auto; width: 100%;">
        <table class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Contacto</th>
                    <th>OT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contactos_ot as $contacto_ot)
                <tr>
                    <td>{{ $contacto_ot->id }}</td>
                    <td>{{ $contacto_ot->contacto->nombre_contacto ?? 'No asignado' }}</td>
                    <td>{{ $contacto_ot->ot->numero_ot ?? 'No asignado' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</details>
