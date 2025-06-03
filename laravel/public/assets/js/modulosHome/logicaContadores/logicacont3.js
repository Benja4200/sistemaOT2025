$(document).ready(function () {
    $.ajax({
        url: "/obtenerOrdenesCantidadContador", // URL del controlador para obtener las ordenes
        method: "GET", // metodo de solicitud
        success: function (response) {
            // 'response' contiene la cantidad de órdenes
            var cantidadOrdenes = response.cantidadOrdenes;

            // funcion para animar el contador
            animateCounter(cantidadOrdenes, ".ordenescontador"); // llamamos la clase para el contador de ordenes
        },
        error: function (xhr, status, error) {
            console.error(
                "Hubo un error al obtener la cantidad de órdenes: ",
                error
            );
        },
    });
});

function animateCounter(targetValue, counterClass) {
    var currentValue = 0;
    var increment = targetValue / 100;
    var duration = 1000;

    var interval = setInterval(function () {
        currentValue += increment;
        if (currentValue >= targetValue) {
            currentValue = targetValue;
            clearInterval(interval);
        }
        $(counterClass).text(Math.round(currentValue));
    }, duration / 100);
}
