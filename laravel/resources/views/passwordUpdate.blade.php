<!-- resources/views/passwordUpdate.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización Automática de Contraseñas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        window.onload = function() {
            // Redirige automáticamente para actualizar las contraseñas
            window.location.href = "{{ route('password.update') }}";
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Actualización de Contraseñas en Progreso...</h1>
        <p class="text-center">Por favor, espera mientras se actualizan las contraseñas...</p>
    </div>
</body>

</html>