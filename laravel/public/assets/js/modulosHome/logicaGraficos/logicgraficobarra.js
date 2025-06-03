$(document).ready(function () {
    // variable global para almacenar el grafico
    var objetoChart = null;

    var meses_spanish = [
        "enero",
        "febrero",
        "marzo",
        "abril",
        "mayo",
        "junio",
        "julio",
        "agosto",
        "septiembre",
        "octubre",
        "noviembre",
        "diciembre",
    ];

    // funcion para cargar los datos del grafico
    function cargarDatosyearx(year) {
        $.ajax({
            url: "/obtener-datos-por-anio", // URL de la API
            method: "GET",
            data: { year: year }, // enviar el year seleccionado
            success: function (response) {
                var meses = meses_spanish; // ["enero", "febrero", ...]
                var registrosPorMes = response.registrosPorMes; // [10, 25, ...] obtenido de la api

                // obtener el id del canvas para el grafico
                var ctx = document.getElementById("weachart").getContext("2d");

                // si ya existe un grafico se destruye
                if (objetoChart) {
                    objetoChart.destroy(); // destruir el grafico anterior
                }

                // crear el grafico de barras
                objetoChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: meses,
                        datasets: [
                            {
                                label: "Registros por Mes",
                                data: registrosPorMes, // datos de registros por mes de la api
                                backgroundColor: "rgba(75, 192, 192, 0.2)", // color de las barras
                                borderColor: "rgba(75, 192, 192, 1)", // color del borde de las barras
                                borderWidth: 1, // ancho del borde de las barras
                            },
                        ],
                    },
                    options: {
                        plugins: {
                            legend: {
                                labels: {
                                    color: "white",
                                },
                            },
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: "white",
                                },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: "white",
                                },
                            },
                        },
                    },
                });
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los datos:", error);
            },
        });
    }

    // cargar los datos automaticamente al cargar la página (usando el valor del año por defecto)
    var year = $("#year").val(); // obtener el year seleccionado
    cargarDatosyearx(year); // llamar a la función para cargar los datos automaticamente

    // cargar los datos al hacer clic en el boton
    $("#cargarDatos").click(function () {
        year = $("#year").val(); // obtener el year seleccionado
        cargarDatosyearx(year); // llamar a la funcion para cargar los datos
    });
});
