// OrdenarSucursal.js

function sortTable(n) {
    var table,
        rows,
        switching,
        i,
        x,
        y,
        shouldSwitch,
        dir,
        switchcount = 0;
    table = document.getElementById("sucursales_tabledata");
    switching = true;
    dir = "asc";
    var headers = table.getElementsByTagName("TH");

    while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < rows.length - 1; i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];

            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }

        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }

    // Quitar las clases de orden de todas las columnas
    for (i = 0; i < headers.length; i++) {
        headers[i].classList.remove("sorted-asc");
        headers[i].classList.remove("sorted-desc");
    }

    // Añadir la clase de orden a la columna ordenada
    if (dir == "asc") {
        headers[n].classList.add("sorted-asc");
    } else {
        headers[n].classList.add("sorted-desc");
    }
}
