document.addEventListener("DOMContentLoaded", function () {
    // Selección de elementos del DOM
    const categoriaSelect = document.getElementById("cod_categoria");
    const subcategoriaSelect = document.getElementById("cod_subcategoria");
    const lineaSelect = document.getElementById("cod_linea");
    const sublineaSelect = document.getElementById("cod_sublinea");
    const marcaSelect = document.getElementById("cod_marca");
    const modeloSelect = document.getElementById("modeloSelect");

    // Limpiar opciones de un selector
    function limpiarOpciones(selectElement) {
        selectElement.innerHTML =
            '<option value="">Seleccionar opción</option>';
    }

    // Llenar el selector con nuevas opciones
    function llenarOpciones(selectElement, data, key) {
        limpiarOpciones(selectElement);
        data.forEach(function (item) {
            let option = document.createElement("option");
            option.value = item.id;
            option.text = item[key];
            selectElement.appendChild(option);
        });
    }

    // Obtener subcategorías por categoría seleccionada
    categoriaSelect.addEventListener("change", function () {
        let categoriaId = this.value;
        if (categoriaId) {
            fetch(`/subcategorias/${categoriaId}`)
                .then((response) => response.json())
                .then((data) => {
                    llenarOpciones(
                        subcategoriaSelect,
                        data,
                        "nombre_subcategoria"
                    );
                    limpiarOpciones(lineaSelect);
                    limpiarOpciones(sublineaSelect);
                });
        } else {
            limpiarOpciones(subcategoriaSelect);
            limpiarOpciones(lineaSelect);
            limpiarOpciones(sublineaSelect);
        }
    });

    // Obtener líneas por subcategoría seleccionada
    subcategoriaSelect.addEventListener("change", function () {
        let subcategoriaId = this.value;
        if (subcategoriaId) {
            fetch(`/lineas/${subcategoriaId}`)
                .then((response) => response.json())
                .then((data) => {
                    llenarOpciones(lineaSelect, data, "nombre_linea");
                    limpiarOpciones(sublineaSelect);
                });
        } else {
            limpiarOpciones(lineaSelect);
            limpiarOpciones(sublineaSelect);
        }
    });

    // Obtener sublíneas por línea seleccionada
    lineaSelect.addEventListener("change", function () {
        let lineaId = this.value;
        if (lineaId) {
            fetch(`/sublineas/${lineaId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.length > 0) {
                        llenarOpciones(sublineaSelect, data, "nombre_sublinea");
                    } else {
                        limpiarOpciones(sublineaSelect);
                    }
                })
                .catch((error) => {
                    console.error("Error al obtener sublíneas:", error);
                    limpiarOpciones(sublineaSelect); // En caso de error, limpiar el selector
                });
        } else {
            limpiarOpciones(sublineaSelect);
        }
    });

    // Obtener modelos por marca y sublínea seleccionada
    sublineaSelect.addEventListener("change", function () {
        let sublineaId = this.value;
        let marcaId = marcaSelect.value;
        if (sublineaId && marcaId) {
            fetch(`/modelos/${marcaId}/${sublineaId}`)
                .then((response) => response.json())
                .then((data) => {
                    llenarOpciones(modeloSelect, data, "nombre_modelo");
                })
                .catch((error) => {
                    console.error("Error al obtener modelos:", error);
                    limpiarOpciones(modeloSelect); // En caso de error, limpiar el selector
                });
        } else {
            limpiarOpciones(modeloSelect);
        }
    });
});
