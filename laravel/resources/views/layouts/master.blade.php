<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SC Informatica</title>

    <!-- Estilos de Bootstrap y Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- actualizado x sebas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="shortcut icon" href="{{ asset('assets/image/logopage_v2.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/components/response_1348x941.css') }}">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('assets/css/global_table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/comp-navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/comp-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/comp-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/comp-pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/comp-cars.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/comp-forms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/l-su-l_sb_df_github.css') }}">

    <!-- Estilos de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/components/sebaczz.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/paraboton.css') }}">
    
     <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    @include('layouts.navbar.header')

    <div>
        <!-- Sidebar -->
        @include('layouts.sidebar.dashboard')

        <!-- Contenido -->
        <div class="content flex-grow-1">
            @yield('content')
        </div>
    </div>

    <!-- Scripts de jQuery y Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- Scripts de DataTables -->
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Sección de Scripts especificas por Vistas -->
    @yield('scripts') <!-- Opcional: para incluir scripts adicionales en vistas especificas -->
    @yield('css')
</body>

</html>
