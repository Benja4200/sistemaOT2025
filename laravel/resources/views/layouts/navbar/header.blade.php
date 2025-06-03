<link rel="stylesheet" href="{{ asset('assets/css/components/comp-navbar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/components/comp-modal-sidebar.css') }}">

<nav style="display: flex; background-color: #333333; justify-content: space-between; padding: 5px; border: 1px solid #ffffff !important; border-left: transparent !important; border-right: transparent !important; border-top: transparent !important; max-width: 100%;">

    <div style="display: flex; width: 40%; height: 60px; align-items: center;">
        <button id="toggleSidebarButton" style="width: 40px; height: 40px; background-color: #333333; border: none; color: #fff; display: flex; justify-content: center; align-items: center; cursor: pointer;">
            <i class="fa fa-bars"></i>
        </button>

        <div style="position: relative; bottom: 6px;">
            <img src="{{ asset('assets/image/logo-small.png') }}" alt="Logo Sc Informatica" class="d-none d-md-block">
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 6px;">
        <div style="border-radius: 100%; padding: 5px; display: flex; align-items: center; width: 50px; height: 50px; background-color: rgb(53, 53, 53); border: 1px solid #c2c2c2;">
            <img src="{{ asset('assets/image/logo-smallchico.png') }}" alt="Logo Sc Informatica">
        </div>

        <a href="{{ route('perfil') }}" style="text-decoration: none; display: flex; padding: 1px; background-color: #bbbbbb; color: #444444; border-radius: 5px; gap: 5px; cursor: pointer;">
            <div style="background-color: #444444; color: #e0e0e0; padding-left: 5px; padding-right: 5px; border-radius: 5px;">
                <p class="m-0" style="font-size: 12px;">{{ auth()->user()->email_usuario ?? 'usuario@example.com' }}</p>
                <p class="m-0" style="font-size: 12px;">
                     Rol:
                     @if (auth()->user()->roles->isNotEmpty())
                         {{ auth()->user()->roles->first()->name }}
                     @else
                         <span class="text-muted">Sin rol</span>
                     @endif
                </p>
            </div>
        </a>
        
        
    </div>
</nav>

<div class="overlay" id="sidebarOverlay"></div>

<script>
    $(document).ready(function() {
        // Asegúrate de que el sidebar tenga la clase 'sidebar' y el botón 'toggleSidebarButton' funcione
        $('#toggleSidebarButton').on('click', function() {
            // Cambiar la visibilidad del sidebar añadiendo/quitando la clase 'open'
            $('#sidebar').toggleClass('open');
            // Mostrar/ocultar el overlay
            $('#sidebarOverlay').toggleClass('active');
        });

        // Cerrar sidebar cuando se hace clic en el overlay
        $('#sidebarOverlay').on('click', function() {
            $('#sidebar').removeClass('open');
            $('#sidebarOverlay').removeClass('active');
        });
    });
</script>

<style>
    /* Estilos para ocultar el sidebar (originalmente 'hidden') */
    /* Estos estilos ya no son necesarios si usas 'open' y 'left' para el sidebar modal */
    /* .hidden {
        display: none !important;
    } */

    /* Estilos para asegurar el botón de menú visible */
    #toggleSidebarButton {
        width: 40px;
        height: 40px;
        background-color: #444444;
        border: none;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    /* Icono del menú */
    #toggleSidebarButton i {
        font-size: 20px;
    }
</style>