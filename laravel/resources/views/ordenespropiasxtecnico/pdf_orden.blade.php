<div
    style="font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; background-color: #f9f9f9; border-radius: 8px;">
    <img src="data:image/jpeg;base64,{{ $imageData }}" alt="Imagen"
        style="border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

    <h1 style="font-size: 24px; font-weight: 600; color: #333; margin-top: 20px;">Detalles de la Orden de Trabajo</h1>

    <div style="margin-top: 20px;">
        <p><strong style="font-size: 18px;">Número de orden:</strong> <span
                style="font-size: 18px; color: #555;">{{ $ordenesDeTecnicos->numero_ot }}</span></p>
        <p><strong style="font-size: 18px;">Cliente:</strong> <span
                style="font-size: 18px; color: #555;">{{ $ordenesDeTecnicos->contacto->sucursal->cliente->nombre_cliente }}</span>
        </p>
        <p><strong style="font-size: 18px;">Sucursal:</strong> <span
                style="font-size: 18px; color: #555;">{{ $ordenesDeTecnicos->contacto->sucursal->nombre_sucursal }}</span>
        </p>
        <p><strong style="font-size: 18px;">Encargado:</strong> <span
                style="font-size: 18px; color: #555;">{{ $ordenesDeTecnicos->tecnicoEncargado->nombre_tecnico }}</span>
        </p>
        <p><strong style="font-size: 18px;">Fecha de orden:</strong> <span
                style="font-size: 18px; color: #555;">{{ $ordenesDeTecnicos->created_at }}</span></p>
        <p><strong style="font-size: 18px;">Fecha de generación del PDF:</strong> <span
                style="font-size: 18px; color: #555;">{{ date('Y-m-d H:i:s') }}</span></p>
    </div>

    <div
        style="margin-top: 20px; border: 1px solid #ddd; background-color: #f2f2f2; padding: 15px; border-radius: 8px;">
        <p style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 10px;">Requerimiento:</p>
        <p style="font-size: 16px; color: #555;">{{ $ordenesDeTecnicos->descripcion_ot }}</p>
    </div>

    <div style="margin-top: 20px;">
        <p style="font-size: 20px; font-weight: 600; color: #333;">Detalles de la Orden</p>
        <p style="font-size: 16px; color: #555;"><strong>Equipo Técnico:</strong>
            {{ $ordenesDeTecnicos->EquipoTecnico[0]['cod_tecnico'] }}</p>
    </div>


    <?php
    $comentario = $ordenesDeTecnicos->comentario_ot;

    $items = explode("//", $comentario);

    $formattedItems = [];

    foreach ($items as $item) {

        $formattedItems[] = trim($item);
    }
    ?>
    <div style="margin-top: 20px; border: 1px solid #ddd; background-color: #f2f2f2; padding: 15px; border-radius: 8px;">
        <p style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 10px;">Cierre de Orden:</p>

        <p>{{ $ordenesDeTecnicos->comentario_ot }}</p>

        <div style="font-size: 16px; color: #555;">
            <ul style="line-height: 1.8;">
                <?php foreach ($formattedItems as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
