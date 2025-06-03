document.addEventListener("DOMContentLoaded", function () {
    // Definimos 'headers' una sola vez al cargar el DOM, usando querySelectorAll
    const headers = document.querySelectorAll("#contactos_tabledata th");

    // Almacena la dirección de ordenación actual para cada columna
    // Inicializa un array para llevar un seguimiento de las direcciones de ordenación:
    // 0: sin ordenar, 1: ascendente, -1: descendente
    const sortDirections = Array(headers.length).fill(0);

    // Adjuntar los event listeners a los encabezados
    headers.forEach((header, index) => {
        header.addEventListener("click", () => {
            sortTable(index); // 'index' es el índice correcto del encabezado clickeado
        });
    });

    function sortTable(n) {
        var table = document.getElementById("contactos_tabledata"); // Obtenemos la tabla dentro de la función
        var rows, switching, i, x, y, shouldSwitch, dir;

        // Determinar la dirección de ordenación inicial basándose en el estado actual de la columna
        if (sortDirections[n] === 0 || sortDirections[n] === -1) { // No ordenada o estaba descendente
            dir = "asc";
            sortDirections[n] = 1; // Actualizar el estado a ascendente
        } else { // Estaba ascendente
            dir = "desc";
            sortDirections[n] = -1; // Actualizar el estado a descendente
        }

        // Reiniciar las direcciones de ordenación de las otras columnas
        for (let k = 0; k < sortDirections.length; k++) {
            if (k !== n) { // Si no es la columna actual, resetear su estado
                sortDirections[k] = 0;
            }
        }

        switching = true;
        while (switching) {
            switching = false;
            rows = table.rows;

            // Recorre todas las filas de la tabla (excepto la primera, que es el encabezado)
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                // Obtén los dos elementos que quieres comparar, uno de la fila actual y otro de la siguiente
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];

                // Obtener el contenido para comparar (convertir a minúsculas para comparación de cadenas)
                var xContent = x.innerHTML.toLowerCase();
                var yContent = y.innerHTML.toLowerCase();

                // Opcional: Manejo de ordenación numérica para columnas que contengan solo números
                // Si ambos contenidos son números válidos (no vacíos), se comparan como números
                if (!isNaN(parseFloat(xContent)) && !isNaN(parseFloat(yContent)) && xContent.trim() !== '' && yContent.trim() !== '') {
                    xContent = parseFloat(xContent);
                    yContent = parseFloat(yContent);
                }


                if (dir == "asc") {
                    if (xContent > yContent) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (xContent < yContent) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }

            if (shouldSwitch) {
                // Si se encontró un par que debe cambiar de lugar, haz el cambio
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }

        // Aplicar las clases CSS DESPUÉS de que la ordenación haya terminado
        resetHeaderClasses(); // Primero, elimina todas las clases de ordenación existentes
        if (dir == "asc") {
            headers[n].classList.add("sorted-asc"); // Aplica la clase a la cabecera correcta
        } else {
            headers[n].classList.add("sorted-desc"); // Aplica la clase a la cabecera correcta
        }
    }

    // Función para eliminar todas las clases de ordenación de los encabezados
    function resetHeaderClasses() {
        headers.forEach((header) => {
            header.classList.remove("sorted-asc", "sorted-desc");
        });
    }
});