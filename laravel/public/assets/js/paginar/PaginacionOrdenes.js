document.addEventListener("DOMContentLoaded", function () {
    const headers = document.querySelectorAll("#ot_tabledata th");

    headers.forEach((header, index) => {
        header.addEventListener("click", () => {
            sortOtTable(index);
        });
    });

    function sortOtTable(n) {
        let table = document.getElementById("ot_tabledata");
        let rows = Array.from(table.rows).slice(1); // Ignorar la primera fila (encabezados)
        let switching = true;
        let dir = "asc";

        while (switching) {
            switching = false;

            rows.forEach((row, rowIndex) => {
                let shouldSwitch = false;
                let x = row.getElementsByTagName("td")[n];
                let y = rows[rowIndex + 1]
                    ? rows[rowIndex + 1].getElementsByTagName("td")[n]
                    : null;

                if (y) {
                    if (
                        (dir === "asc" &&
                            x.innerHTML.toLowerCase() >
                                y.innerHTML.toLowerCase()) ||
                        (dir === "desc" &&
                            x.innerHTML.toLowerCase() <
                                y.innerHTML.toLowerCase())
                    ) {
                        shouldSwitch = true;
                        switching = true;
                        row.parentNode.insertBefore(rows[rowIndex + 1], row);
                    }
                }
            });

            // Cambiar dirección del ordenamiento si no hubo cambios en esta pasada y estamos en orden ascendente
            if (!switching && dir === "asc") {
                dir = "desc";
                switching = true;
            }
        }

        // Cambiar clases para indicar la columna ordenada
        resetHeaderClasses();
        headers[n].classList.add(dir === "asc" ? "sorted-asc" : "sorted-desc");
    }

    function resetHeaderClasses() {
        headers.forEach((header) => {
            header.classList.remove("sorted-asc", "sorted-desc");
        });
    }
});
