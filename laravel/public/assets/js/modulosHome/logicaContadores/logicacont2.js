$(document).ready(function () {
    // llamamos a Ajax para obtener la cantidad de clientes
    $.ajax({
        url: "/clientescantidad", // URL de tu controlador
        method: "GET", // metodo de solicitud
        success: function (response) {
            // 'response' contiene la cantidad de clientes
            var cantidadClientes = response.clientesCantidad;

            // funcion para animar el contador
            animateCounterx(cantidadClientes);
        },
        error: function (xhr, status, error) {
            console.error(
                "Hubo un error al obtener la cantidad de clientes: ",
                error
            );
        },
    });
});

function animateCounterx(targetValue) {
    var currentValue = 0; // valor inicial
    var increment = targetValue / 100; // incremento por cada paso (ajustable)
    var duration = 1000; // duracion total de la animacion en (1 segundo)

    var interval = setInterval(function () {
        currentValue += increment;
        if (currentValue >= targetValue) {
            currentValue = targetValue; // asegurarse de que no sobrepase el valor
            clearInterval(interval); // detener la animacion cuando llegue al valor final
        }
        $(".counter").text(Math.round(currentValue)); // Actualizar el contador
    }, duration / 100); // dividimos el tiempo entre 100 para hacer la animacion más fluida
}
