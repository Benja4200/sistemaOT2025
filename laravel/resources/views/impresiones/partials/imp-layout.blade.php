<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="icon"  href="{{ asset('assets/image/logopage_v2.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            margin: 0;
            position: relative;
            min-height: 100vh; /* Asegura que el cuerpo ocupe al menos la altura de la ventana */
        }
        main {
            padding-bottom: 150px; /* Espacio para el footer */
        }
        footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px; /* Ajusta la altura del footer según sea necesario */
        }
        
    </style>
</head>
<body>
    <header class="header">
       <div class="float-end" style="border:1px solid black; margin:0; padding: 2px 5px 2px 5px;">
    <div style="font-size:9px; text-align: left;">
        Cliente: {{ html_entity_decode($informe->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente ?? 'n/a') }}<br>
        Sucursal: {{ html_entity_decode($informe->contactoOt[0]->contacto->sucursal->nombre_sucursal ?? 'n/a') }}<br>
        Encargado: {{ html_entity_decode($informe->tecnicoEncargado->nombre_tecnico ?? 'n/a') }}<br>
        Fecha Orden: {{ isset($informe->created_at) ? date('d-m-Y', strtotime($informe->created_at)) : 'n/a' }}<br>
        Fecha Documento: {{ date('d-m-Y') }} <!-- Muestra la fecha actual -->
    </div>
</div>
        <div class="row text-center mb-3">
            <div class="col-md-12">
                <img src="{{ asset('assets/image/LogoCli.png') }}" width="10%" height="auto" alt="Logo">
                <div class="ms-3 d-inline-block">
                    <h3 class="mb-0">{{ env('APP_PUBLIC_NAME') }}</h3>
                    <h3 class="text-center mb-0">@yield('informe-titulo')</h3>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>
    <footer>
        <div class="container-fluid text-center" style="width: 95%;">
            <div class="row">
                <div class="col-md-12">
                    <div style="margin-top: 20px; padding-top: 10px; display: table; width: 100%; font-size: 14px;border:1px solid black;padding:10px;border-radius:5px;background-color: #f2f2f2;">
                        <div style="display: table-row;">
                            <div style="display: table-cell; text-align: left; width: 50%; height: 80px;">
                                <h4>Teléfono: 02-8840171</h4>
                                <h4>Celular: +56 9 74985258</h4>
                                <h4>Email: serviciossc@scinformatica.cl</h4>
                            </div>
                            <div style="display: table-cell; text-align: left; width: 50%; height: 80px;">
                                <h4>Cliente: {{ html_entity_decode($informe->contactoOt[0]->contacto->sucursal->cliente->nombre_cliente ?? 'n/a') }}</h4>
                                <h4>Contacto: {{ html_entity_decode($informe->contactoOt[0]->contacto->nombre_contacto ?? 'n/a') }}</h4>
                            </div>
                        </div>
                        <div style="display: table-row; position: relative;">
                            <div style="display: table-cell; text-align: left; width: 50%; position: relative;">
                                <p>Firma SC Informática</p>
                                <p>_____________________</p>
                                @if($firmaSc)
                                    <img src="{{ $firmaSc }}" alt="Firma SC Electrónica" style="width: 100%; max-width: 200px; height: auto; position: absolute; top: 15px; left: 0;">
                                @endif
                                 <!-- Guiones bajos para simular el lugar de la firma -->
                            </div>
                            <div style="display: table-cell; text-align: left; width: 50%; position: relative;">
                                <p>Firma Cliente</p>
                                <!-- Aquí puedes agregar la firma del cliente si es necesario -->
                                <p>_____________________</p> <!-- Guiones bajos para simular el lugar de la firma -->
                                @if($firmaCliente)
                                    <!-- Contenedor fijo para la firma con borde para depuración -->
                                    <div style="position: relative; width: 180px; height: 80px;">
                                        <!-- Cuadro de la firma con posición ajustada y borde adicional -->
                                        <div style="position: absolute; top: -70px; left: 0; width: 180px; height: 80px;">
                                            <img src="{{ $firmaCliente }}" alt="Firma Cliente" style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- jQuery 3.x -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
            integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>