document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById('tecnicos_tabledata'); // Asegúrate que este ID coincida con tu tabla
    if (!table) {
        console.error("Tabla con ID 'tecnicos_tabledata' no encontrada.");
        return;
    }
    const headers = table.querySelectorAll('th[onclick^="sortTable"]');

    // Almacenamos el índice de la columna actualmente ordenada y su dirección
    table.currentSortColIndex = -1; // -1 significa que ninguna columna está ordenada inicialmente
    table.currentSortDir = 'asc';   // Dirección de ordenación predeterminada

    // Hacemos que la función sortTable sea accesible globalmente para los atributos onclick
    window.sortTable = function(colIndex) {
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const isNumeric = (s) => !isNaN(parseFloat(s)) && isFinite(s);

        // Determinar la dirección de ordenación para la columna clicada
        if (table.currentSortColIndex === colIndex) {
            // Si se hace clic en la misma columna, alternar la dirección
            table.currentSortDir = (table.currentSortDir === 'asc') ? 'desc' : 'asc';
        } else {
            // Si se hace clic en una columna diferente, reiniciar a 'asc' y actualizar la columna ordenada
            table.currentSortDir = 'asc';
            // Si había una columna ordenada previamente, quitar sus clases de ordenación
            if (table.currentSortColIndex !== -1 && headers[table.currentSortColIndex]) {
                headers[table.currentSortColIndex].classList.remove('sorted-asc', 'sorted-desc');
            }
            table.currentSortColIndex = colIndex; // Establecer la nueva columna ordenada
        }

        // Ordenar las filas
        rows.sort((a, b) => {
            let aText = a.cells[colIndex].textContent.trim();
            let bText = b.cells[colIndex].textContent.trim();

            // Comparación numérica (si los valores son numéricos y no están vacíos)
            if (isNumeric(aText) && isNumeric(bText) && aText !== '' && bText !== '') {
                const aNum = parseFloat(aText);
                const bNum = parseFloat(bText);
                return table.currentSortDir === 'asc' ? aNum - bNum : bNum - aNum;
            }

            // Comparación de cadenas (insensible a mayúsculas/minúsculas)
            aText = aText.toLowerCase();
            bText = bText.toLowerCase();
            if (aText < bText) return table.currentSortDir === 'asc' ? -1 : 1;
            if (aText > bText) return table.currentSortDir === 'asc' ? 1 : -1;
            return 0; // Si son iguales
        });

        // Re-adjuntar las filas ordenadas al tbody para actualizar el DOM
        rows.forEach(row => tbody.appendChild(row));

        // Actualizar las clases CSS para los indicadores de ordenación
        // Primero, eliminar todas las clases de ordenación de todos los encabezados
        headers.forEach(header => {
            header.classList.remove('sorted-asc', 'sorted-desc');
        });
        // Luego, añadir la clase correcta al encabezado de la columna actualmente ordenada
        if (headers[colIndex]) {
            headers[colIndex].classList.add(`sorted-${table.currentSortDir}`);
        }
    };
});