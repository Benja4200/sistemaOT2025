document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById('tareas_tabledata');
    if (!table) {
        console.error("Tabla con ID 'tareas_tabledata' no encontrada.");
        return;
    }
    const headers = table.querySelectorAll('th[onclick^="sortTable"]');

    table.currentSortColIndex = -1;
    table.currentSortDir = 'asc';

    window.sortTable = function(colIndex) {
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const isNumeric = (s) => !isNaN(parseFloat(s)) && isFinite(s);

        if (table.currentSortColIndex === colIndex) {
            table.currentSortDir = (table.currentSortDir === 'asc') ? 'desc' : 'asc';
        } else {
            table.currentSortDir = 'asc';
            if (table.currentSortColIndex !== -1 && headers[table.currentSortColIndex]) {
                headers[table.currentSortColIndex].classList.remove('sorted-asc', 'sorted-desc');
            }
            table.currentSortColIndex = colIndex;
        }

        rows.sort((a, b) => {
            let aText = a.cells[colIndex].textContent.trim();
            let bText = b.cells[colIndex].textContent.trim();

            if (isNumeric(aText) && isNumeric(bText) && aText !== '' && bText !== '') {
                const aNum = parseFloat(aText);
                const bNum = parseFloat(bText);
                return table.currentSortDir === 'asc' ? aNum - bNum : bNum - aNum;
            }

            aText = aText.toLowerCase();
            bText = bText.toLowerCase();
            if (aText < bText) return table.currentSortDir === 'asc' ? -1 : 1;
            if (aText > bText) return table.currentSortDir === 'asc' ? 1 : -1;
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));

        headers.forEach(header => {
            header.classList.remove('sorted-asc', 'sorted-desc');
        });
        if (headers[colIndex]) {
            headers[colIndex].classList.add(`sorted-${table.currentSortDir}`);
        }
    };
});