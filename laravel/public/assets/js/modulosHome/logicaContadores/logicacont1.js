$(document).ready(function () {
    // hacemos el llamado Ajax para obtener la cantidad de marcas
    $.ajax({
        url: "/marcas/cantidad", // URL del controlador que devuelve la cantidad de marcas
        method: "GET", // metodo de solicitud
        success: function (response) {
            // 'response' contiene la cantidad de marcas
            var cantidadMarcas = response.cantidadMarcas; // respuesta con la clave "cantidadMarcas" del json

            // funcion para animar el contador
            animateCounterv3(cantidadMarcas, ".counterMarcas"); // usamos la clase .counterMarcas y los datos para animar el elemento con esa clase
        },
        error: function (xhr, status, error) {
            console.error(
                "Hubo un error al obtener la cantidad de marcas: ",
                error
            );
        },
    });
});

// funcion para animar el contador sacada de stackoverflow
function animateCounterv3(targetValue, counterClass) {
    var currentValue = 0; // valor inicial
    var increment = targetValue / 100; // incremento por cada paso (ajustable)
    var duration = 1000; // duracion total de la animacion (1 segundo)

    var interval = setInterval(function () {
        currentValue += increment;
        if (currentValue >= targetValue) {
            currentValue = targetValue; // asegurarse de que no sobrepase el valor
            clearInterval(interval); // detener la animacion cuando llegue al valor final
        }
        $(counterClass).text(Math.round(currentValue)); // actualizar el contador con la clase especifica
    }, duration / 100); // dividimos el tiempo entre 100 para hacer la animacion más fluida
}
