document.addEventListener("DOMContentLoaded", function () {
    // Función para manejar los mensajes de éxito
    function handleSuccessMessage() {
        const successMessageElement =
            document.getElementById("success-message") ||
            document.getElementById("success-message-edit");

        if (successMessageElement) {
            const successType =
                document.getElementById("success-type").textContent;
            const moduleName =
                document.getElementById("module-name").textContent;
            const redirectUrl =
                document.getElementById("redirect-url").textContent;

            let successMessage = "Acción realizada correctamente."; // Mensaje por defecto
            switch (successType) {
                case "agregar":
                    successMessage = `${moduleName} agregado correctamente.`;
                    break;
                case "editar":
                    successMessage = `${moduleName} editado correctamente.`;
                    break;
                case "eliminar":
                    successMessage = `${moduleName} eliminado correctamente.`;
                    break;
            }

            Swal.fire({
                title: "Éxito",
                text: successMessage,
                icon: "success",
                timer: 1500, // Tiempo en milisegundos
                showConfirmButton: false,
                confirmButtonColor: "#cc6633", // Mismo color de confirmación
                position: "center", // Posición centrada
            }).then(() => {
                // Redirigir después de mostrar el mensaje de éxito
                window.location.href = redirectUrl;
            });
        }
    }

    handleSuccessMessage();

    // Función para manejar la confirmación de eliminación
    function handleDeleteConfirmation() {
    document.querySelectorAll(".btn-danger").forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Prevenir la acción por defecto
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#cc6633",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar la alerta de éxito
                    Swal.fire({
                        title: "Eliminado",
                        text: "Elemento eliminado correctamente.",
                        icon: "success",
                        timer: 3000, // Mantener la alerta visible durante 3 segundos
                        timerProgressBar: true, // Mostrar barra de progreso del temporizador
                        willClose: () => {
                            // Enviar el formulario después de que la alerta se cierre
                            this.closest("form").submit();
                        }
                    });
                }
            });
        });
    });
}

handleDeleteConfirmation();
});
