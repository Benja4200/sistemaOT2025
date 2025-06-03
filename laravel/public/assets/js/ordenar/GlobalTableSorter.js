document.addEventListener("DOMContentLoaded", function () {
    /**
     * Función global para ordenar tablas HTML.
     * @param {HTMLElement} headerElement El elemento <th> que fue clickeado (pasado como 'this' desde el onclick).
     * @param {number} colIndex El índice de la columna a ordenar (0-basado).
     */
    window.sortTable = function(headerElement, colIndex) {
        // Obtener la tabla padre desde el encabezado <th> que fue clickeado
        const table = headerElement.closest('table'); 

        if (!table) {
            console.error("Error: No se pudo encontrar la tabla padre para el encabezado clicado.");
            return;
        }

        // Seleccionar todos los encabezados <th> dentro de esta tabla específica que son ordenables
        const headers = table.querySelectorAll('th[onclick^="sortTable"]');
        
        // Si la tabla aún no tiene propiedades de ordenación (`currentSortColIndex`, `currentSortDir`), inicializarlas
        // Esto permite que múltiples tablas en la misma página se ordenen independientemente
        if (typeof table.currentSortColIndex === 'undefined') {
            table.currentSortColIndex = -1; // Ninguna columna ordenada al inicio
            table.currentSortDir = 'asc';   // Dirección predeterminada
        }

        const tbody = table.tBodies[0]; // Obtener el cuerpo de la tabla
        const rows = Array.from(tbody.querySelectorAll('tr')); // Convertir las filas a un array para poder ordenarlas
        
        // Función auxiliar para verificar si un valor es numérico
        const isNumeric = (s) => !isNaN(parseFloat(s)) && isFinite(s);

        // Lógica para determinar la nueva dirección de ordenación
        if (table.currentSortColIndex === colIndex) {
            // Si se hace clic en la misma columna que ya estaba ordenada, alternar la dirección (asc/desc)
            table.currentSortDir = (table.currentSortDir === 'asc') ? 'desc' : 'asc';
        } else {
            // Si se hace clic en una columna diferente:
            // 1. Reiniciar la dirección a 'asc' (es el comportamiento común para una nueva columna de ordenación)
            table.currentSortDir = 'asc';
            // 2. Si había una columna ordenada previamente, remover las clases CSS que indican su estado de ordenación
            if (table.currentSortColIndex !== -1 && headers[table.currentSortColIndex]) {
                headers[table.currentSortColIndex].classList.remove('sorted-asc', 'sorted-desc');
            }
            // 3. Establecer el nuevo índice de la columna actualmente ordenada
            table.currentSortColIndex = colIndex; 
        }

        // Ordenar las filas del array
        rows.sort((a, b) => {
            // Obtener el texto de las celdas de la columna actual para comparar
            let aText = a.cells[colIndex].textContent.trim();
            let bText = b.cells[colIndex].textContent.trim();

            // Intentar comparar como números si ambos valores son numéricos y no vacíos
            if (isNumeric(aText) && isNumeric(bText) && aText !== '' && bText !== '') {
                const aNum = parseFloat(aText);
                const bNum = parseFloat(bText);
                return table.currentSortDir === 'asc' ? aNum - bNum : bNum - aNum; // Orden numérico
            }

            // Si no son números o están vacíos, comparar como cadenas (insensible a mayúsculas/minúsculas)
            aText = aText.toLowerCase();
            bText = bText.toLowerCase();
            if (aText < bText) return table.currentSortDir === 'asc' ? -1 : 1; // Orden alfabético
            if (aText > bText) return table.currentSortDir === 'asc' ? 1 : -1;
            return 0; // Si los valores son iguales
        });

        // Re-adjuntar las filas ordenadas al tbody de la tabla
        // Esto automáticamente reordena las filas visualmente en el DOM
        rows.forEach(row => tbody.appendChild(row));

        // Actualizar las clases CSS en los encabezados para mostrar los indicadores visuales de ordenación (flechas)
        // Primero, remover todas las clases de ordenación de todos los encabezados de esta tabla
        headers.forEach(header => {
            header.classList.remove('sorted-asc', 'sorted-desc');
        });
        // Luego, añadir la clase CSS correcta al encabezado de la columna que acaba de ser ordenada
        if (headers[colIndex]) {
            headers[colIndex].classList.add(`sorted-${table.currentSortDir}`);
        }
    };
});