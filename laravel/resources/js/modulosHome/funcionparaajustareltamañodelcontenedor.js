// funcion para ajustar el tamaño del contenedor
function adjustContainerWidth() {
    const width = document.documentElement.clientWidth; // ancho del HTML (ventana visible)

    const selectorContainer = document.querySelector(
        ".selector-container-zelgaria"
    );

    // aplicar el ancho dinamicamente usando el ancho de la ventana
    selectorContainer.style.width = width * 0.46 + "px"; // ajustar al 60% del ancho de la ventana
}

// llamar a la funcion al cargar la pagina y al redimensionar la ventana
window.addEventListener("resize", adjustContainerWidth);
window.addEventListener("load", adjustContainerWidth); // aplicar el estilo al cargar