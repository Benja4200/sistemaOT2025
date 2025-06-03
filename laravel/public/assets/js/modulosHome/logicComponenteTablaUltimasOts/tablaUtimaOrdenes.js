// Hacer la solicitud GET a la API cuando la página cargue
$(document).ready(function () {
    fetchOts();
});

// Función para formatear la fecha
function formatDate(dateString) {
    const date = new Date(dateString); // Convierte la cadena de fecha en un objeto Date
    const options = {
        weekday: "long", // Día de la semana (opcional)
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    };
    return date.toLocaleDateString("es-ES", options); // Devuelve la fecha en formato español
}

// Función para obtener los datos de la API
function fetchOts() {
    $.ajax({
        url: "https://mediumaquamarine-gnat-166914.hostingersite.com/api/ots",
        method: "GET",
        success: function (response) {
            // Limpiar la tabla antes de insertar los nuevos datos
            $("#otsTableBody").empty();

            // Iterar sobre los datos y agregar filas a la tabla
            response.data.forEach(function (ot) {
                const formattedCreatedAt = ot.created_at
                    ? formatDate(ot.created_at)
                    : "N/A";

                // Lógica para determinar el color según el estado
                let estadoStyle = "";
                let estadoText = "";
                if (ot.estado.descripcion_estado_ot === "Iniciada") {
                    estadoStyle = "background-color: green; color: white;";
                    estadoText = "Iniciada";
                } else if (ot.estado.descripcion_estado_ot === "Pendiente") {
                    estadoStyle = "background-color: yellow; color: black;";
                    estadoText = "Pendiente";
                } else if (ot.estado.descripcion_estado_ot === "Finalizada") {
                    estadoStyle = "background-color: red; color: white;";
                    estadoText = "Finalizada";
                } else {
                    estadoText = "N/A";
                }

                const row = `
                    <tr style="font-size: 12px;">
                        <td style="padding: 8px 16px;">${ot.numero_ot}</td>
                        <td style="padding: 8px 16px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 50px;">${
                            ot.descripcion_ot
                        }</td>
                        <td style="padding: 8px 16px;">${ot.horas_ot}</td>
                        <td style="padding: 8px 16px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 50px;">${
                            ot.comentario_ot
                        }</td>
                        <td style="padding: 8px 16px;">${
                            ot.contacto ? ot.contacto.nombre_contacto : "N/A"
                        }</td>
                        <td style="padding: 8px 16px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 50px;">${
                            ot.servicio ? ot.servicio.nombre_servicio : "N/A"
                        }</td>
                        <td style="padding: 8px 16px;">${
                            ot.tecnico_encargado
                                ? ot.tecnico_encargado.nombre_tecnico
                                : "N/A"
                        }</td>

                        <td class="px-4 py-2">
                            <p style="padding: 5px; border-radius: 5px; text-align: center; ${estadoStyle}">${estadoText}</p>
                        </td>

                        <td style="padding: 8px 16px;">${
                            ot.tipo_visita
                                ? ot.tipo_visita.descripcion_tipo_visita
                                : "N/A"
                        }</td>
                        <td style="padding: 8px 16px;">${
                            ot.prioridad
                                ? ot.prioridad.descripcion_prioridad_ot
                                : "N/A"
                        }</td>
                        <td style="padding: 8px 16px;">${
                            ot.tipo ? ot.tipo.descripcion_tipo_ot : "N/A"
                        }</td>
                        <td style="padding: 8px 16px;">${
                            ot.fecha_fin_planificada_ot || "N/A"
                        }</td>
                        <td style="padding: 8px 16px;">${formattedCreatedAt}</td>
                    </tr>
                `;
                $("#otsTableBody").append(row); // Agregar la fila a la tabla
            });
        },
        error: function (xhr, status, error) {
            console.log("Error: " + error);
        },
    });
}
