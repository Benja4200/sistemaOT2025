// variable global para almacenar el grafico
var ordenesChartjs = null;

$(document).ready(function () {
    // Función para destruir el grafico si ya existe
    function destroyChart(ordenesChartjs) {
        if (ordenesChartjs) {
            ordenesChartjs.destroy(); // Destruir el grafico existente
        }
    }

    // funcion para cargar los datos y actualizar el grafico
    function cargarDatos(year, month) {
        // solicitud AJAX para obtener los datos según el year y mes seleccionados
        $.ajax({
            url: "/obtener-datos-ordenes", // Ruta que definimos en web.php
            method: "GET",
            data: {
                year: year, // enviar el year seleccionado a la URL
                month: month, // enviar el mes seleccionado
            },
            success: function (data) {
                // actualizar los contadores en la pagina con la data obtenida de la URL
                $("#pendientes-count").text(data.ordenesPendientes);
                $("#finalizadas-count").text(data.ordenesFinalizadas);
                $("#iniciadas-count").text(data.ordenesIniciadas);

                // destruimos el grafico anterior (si existe) para insertar los datos obtenidos
                destroyChart(ordenesChartjs);

                // ahora creo el grafico nuevo "canvas"
                var ctx = document
                    .getElementById("ordenesChart")
                    .getContext("2d");

                ordenesChartjs = new Chart(ctx, {
                    type: "pie", // tipo de grafico (pastel)
                    data: {
                        labels: ["Pendientes", "Finalizadas", "Iniciadas"], // etiquetas
                        datasets: [
                            {
                                label: "Cantidad de Órdenes",
                                data: [
                                    data.ordenesPendientes,
                                    data.ordenesFinalizadas,
                                    data.ordenesIniciadas,
                                ], // datos de las ordenes
                                backgroundColor: [
                                    "rgba(255, 255, 0)",
                                    "rgba(255, 0, 0)",
                                    "rgba(3, 255, 0, 0.8)",
                                ], // colores de las secciones
                                borderColor: [
                                    "rgba(174, 0, 132, 0.8)",
                                    "rgba(54, 162, 235, 1)",
                                    "rgba(35, 255, 0, 0.8)",
                                ], // colores del borde
                                borderWidth: 1, // ancho del borde
                            },
                        ],
                    },
                    options: {
                        responsive: false, // propiedad para que el grafico sea adaptable al tamaño de la pantalla
                        plugins: {
                            legend: {
                                position: "top", // posicion de la leyenda
                                labels: {
                                    color: "white",
                                },
                            },
                            // recomendacion de stack overflow
                            tooltip: {
                                callbacks: {
                                    label: function (tooltipItem) {
                                        // formateo de las etiquetas en el tooltip
                                        return (
                                            tooltipItem.label +
                                            ": " +
                                            tooltipItem.raw +
                                            " ordenes"
                                        );
                                    },
                                },
                            },
                        },
                    },
                });
            },
            error: function (xhr, status, error) {
                console.error("Error al obtener los datos:", error);
            },
        });
    }

    // cargar los datos automaticamente al cargar la pagina (usando los valores de año y mes predeterminados)
    var year = $("#year").val(); // obtener el year seleccionado
    var month = $("#month").val(); // obtener el mes seleccionado
    cargarDatos(year, month); // llamar a la función para cargar los datos automaticamente

    // cargar los datos al hacer clic en el boton
    $("#cargarDatos").click(function () {
        year = $("#year").val(); // obtener el year seleccionado
        month = $("#month").val(); // obtener el mes seleccionado
        cargarDatos(year, month); // llamar a la función para cargar los datos
    });
});
