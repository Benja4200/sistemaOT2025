document.addEventListener("DOMContentLoaded", function () {
    const headers = document.querySelectorAll("#ot_tabledata th");

    headers.forEach((header, index) => {
        header.addEventListener("click", () => {
            sortOtTable(index);
        });
    });

    function sortOtTable(n) {
        let table,
            rows,
            switching,
            i,
            x,
            y,
            shouldSwitch,
            dir,
            switchcount = 0;
        table = document.getElementById("ot_tabledata");
        switching = true;
        dir = "asc";

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

        // Cambiar clases para indicar la columna ordenada
        resetHeaderClasses();
        if (dir === "asc") {
            headers[n].classList.add("sorted-asc");
        } else {
            headers[n].classList.add("sorted-desc");
        }
    }

    function resetHeaderClasses() {
        headers.forEach((header) => {
            header.classList.remove("sorted-asc", "sorted-desc");
        });
    }
});
