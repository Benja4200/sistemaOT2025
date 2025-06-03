<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <!-- Iconos y estilos -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/materialize.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/image/logopage_v2.png') }}" type="image/x-icon">
</head>

<body class="dark-background">
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Imagen lateral -->
            <div class="login-image-wrapper">
                <img src="{{ asset('assets/image/logo-small.png') }}" alt="Logo" class="login-logo">
            </div>

            <!-- Formulario -->
            <div class="login-form-wrapper">
                <h5 class="center-align">INGRESE A SU CUENTA</h5>
                <p class="center-align" style="color: #CCCCCC;">Ingrese sus credenciales a continuación</p>

                <!-- Formulario de inicio de sesión -->
                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf

                    <!-- Campo de Correo Electrónico -->
                    <div class="input-field">
                        <i class="material-icons prefix">email</i>
                        <input style="padding-left: 5px; margin-top: 5px; border-radius: 5px;" type="email" id="email" name="email" value="{{ old('email') }}" required>
                        <label for="email">Correo Electrónico</label>
                    </div>

                    <!-- Campo de Contraseña -->
                    <div class="input-field">
                        <i class="material-icons prefix" style="color: #FF9900;">lock</i>
                        <input style="padding-left: 5px; margin-top: 5px; border-radius: 5px;" type="password" id="password" name="password" required>
                        <label for="password">Contraseña</label>

                    </div>

                    <!-- Botón de envío -->
                    <div class="input-field center-align" style="margin-top: 20px;">
                        <button type="submit" class="btn waves-effect waves-light login-btn">Ingresar</button>
                    </div>
                    
                    <style>
                        /* Estilo para el mensaje de error */
                        .error-message {
                            background-color: #f1f1f1;  /* Gris muy suave de fondo */
                            color: #333;  /* Texto en gris oscuro */
                            border-left: 5px solid #e74c3c;  /* Línea roja para destacar el error */
                            border-radius: 10px;  /* Bordes redondeados */
                            padding: 5px 5px;  /* Espaciado interno */
                            margin-top: 15px;  /* Espaciado superior */
                            display: flex;
                            align-items: center;
                            font-size: 16px;
                            transition: opacity 0.5s ease; /* Transición suave si se quiere ocultar */
                        }
                        
                        /* Estilo para el ícono de advertencia */
                        .error-icon {
                            font-size: 20px;
                            margin-right: 10px; /* Espaciado entre el ícono y el texto */
                        }
                        
                        /* Estilo para el texto del mensaje */
                        .error-text {
                            flex-grow: 1;
                            display: inline-block;
                        }
                        
                        /* Estilo adicional para el mensaje después de 3 segundos */
                        .error-message.hidden {
                            opacity: 0;
                            pointer-events: none; /* Asegura que no interrumpa la interacción */
                        }

                    </style>

                    <!-- Mostrar el error global -->
                    @if (session('error_global'))
                        <div class="error-message" id="error-global">
                            <span class="error-icon">⚠️</span>
                            <span class="error-text">{{ session('error_global') }}</span>
                        </div>
                    @endif
                    
                    <!-- Mostrar errores de los campos de email y password -->
                    @error('email')
                        <div class="error-message" id="error-email">
                            <span class="error-icon">⚠️</span>
                            <span class="error-text">{{ $message }}</span>
                        </div>
                    @enderror
                    @error('password')
                        <div class="error-message" id="error-password">
                            <span class="error-icon">⚠️</span>
                            <span class="error-text">{{ $message }}</span>
                        </div>
                    @enderror
                    
                    <script>
                        // funcion para ocultar el mensaje de error 3 segundos
                        setTimeout(function() {
                            // ocultar el mensaje global de error
                            const errorGlobal = document.getElementById('error-global');
                            const errorEmail = document.getElementById('error-email');
                            const errorPassword = document.getElementById('error-password');
                    
                            if (errorGlobal) {
                                errorGlobal.style.display = 'none';
                            }
                            if (errorEmail) {
                                errorEmail.style.display = 'none';
                            }
                            if (errorPassword) {
                                errorPassword.style.display = 'none';
                            }
                        }, 3000);
                    </script>

                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
</body>

</html>
