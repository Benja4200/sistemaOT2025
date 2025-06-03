@component('mail::message')
<!-- Encabezado con imagen y saludo -->
<div style="text-align: center;">
    <img src="https://drive.google.com/uc?export=download&id=1hTk5ZVvHS3qBb3yThfmsy7pS8vySIxjY" alt="" style="max-width: 100%; height: auto;">
    <h2 style="margin: 10px 0; color: #333;">Hola, {{ $notifiable->nombre_usuario }}</h2>
</div>

<!-- Título principal -->
<h3 style="text-align: center; color: #333;">Tienes una nueva orden de trabajo</h3>

<!-- Información de tipo y descripción -->
<h4 style="text-align: center; color: #333;">Tipo: {{ $orden->tipo->descripcion_tipo_ot ?? 'N/A' }}</h4>
<h4 style="text-align: center; color: #333;">{{ $orden->descripcion_ot ?? 'Sin descripción' }}</h4>

<!-- Tabla con información general de la orden -->
@component('mail::table')
| #Orden | Cliente | Sucursal | Servicio | Fecha |
|:------:|:-------:|:--------:|:--------:|:-----:|
| {{ $orden->numero_ot }} | {{ $orden->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente ?? 'N/A' }} | {{ $orden->contactoOt[0]->contacto->sucursal->nombre_sucursal ?? 'N/A' }} | {{ $orden->servicio->nombre_servicio ?? 'N/A' }} | {{ $orden->created_at->format('d/m/Y') }} |
@endcomponent

<br>

<!-- Tabla con información de responsables y detalles adicionales (sin la columna 'Técnico') -->
@component('mail::table')
| Responsable | Prioridad | Horas |
|:-----------:|:---------:|:-----:|
| {{ $orden->tecnicoEncargado->nombre_tecnico ?? 'N/A' }} | {{ $orden->prioridad->descripcion_prioridad_ot ?? 'N/A' }} | {{ $orden->horas_ot ?? 'N/A' }} |
@endcomponent

<br>

<!-- Lista de todos los técnicos del equipo -->
<h4 style="color: #333;">Técnicos Asignados:</h4>
<ul>
    @foreach ($orden->EquipoTecnico as $EquipoTecnico)
        <li>{{ html_entity_decode($EquipoTecnico->tecnico->nombre_tecnico) }}</li>
    @endforeach
</ul>

<!-- Pie de mensaje -->
<p style="font-size: 12px; color: #888; text-align: center; margin-top: 20px;">
    Este correo es generado automáticamente. Por favor, no es necesario responder.
</p>
<p style="font-size: 12px; color: #888; text-align: center;">
    &copy; {{ date('Y') }} SCINFORMATICA. Todos los derechos reservados.
</p>
@endcomponent
