<aside id="sidebar" class="sidebar" style="">
    
    <nav class="navbarx3 navbar-expand navbar-dark" style="min-height: 100%;">

        <ul class="navbar-nav" style="padding: 4px;">


        @if (auth()->user()->roles->isNotEmpty())

        @if (auth()->user()->roles->contains('name', 'Administrador'))
            <div style="display: flex; padding-left: 20px; padding-bottom: 10px;  flex justify-center;" class="mt-2 mx-3 bg-[#333333] px-[10px] py-[10px] flex justify-center">
                <img class="" style="width: 106px;" src="{{ asset('assets/image/logo-small.png') }}" alt="Logo">
            </div>
        @endif

    @endif

    @if (auth()->user()->roles->isNotEmpty())

        @if (auth()->user()->roles->first()->name === 'Tecnicos')

            <div style="display: flex; padding-left: 20px; padding-bottom: 10px;  flex justify-center;" class="mt-2 mx-3 bg-[#333333] px-[10px] py-[10px] flex justify-center">
                <img class="" style="width: 106px;" src="{{ asset('assets/image/logo-small.png') }}" alt="Logo">
            </div>

        @endif

    @endif

            @if (auth()->user()->roles->isNotEmpty())

                @if (auth()->user()->roles->first()->name === 'Administrador')
                
                    <!-- Home estidisticas -->
                    @can('ordenes.index')

                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('home*') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fa-solid fa-house"></i> <span>Home</span>
                            </a>
                        </li>
                    @endcan

                    <!-- Ordenes -->
                    @can('ordenes.index')

                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('ordenes*') ? 'active' : '' }}" href="{{ route('ordenes.index') }}">
                                <i class="fas fa-shopping-cart"></i> <span>Ordenes</span>
                            </a>
                        </li>
                    @endcan

                    <!-- Clientes -->
                    @can('clientes.index')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('clientes*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                                <i class="fas fa-user-friends"></i> <span>Clientes</span>
                            </a>
                        </li>
                    @endcan

                    <!-- Sucursales -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('sucursales*') ? 'active' : '' }}"
                            href="{{ route('sucursales.index') }}">
                            <i class="fas fa-building"></i> <span>Sucursales</span>
                        </a>
                    </li>

                    <!-- Contactos -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('contactos*') ? 'active' : '' }}"
                            href="{{ route('contactos.index') }}">
                            <i class="fas fa-address-book"></i> <span>Contactos</span>
                        </a>
                    </li>

                    <!-- Servicios -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('servicios*') ? 'active' : '' }}"
                            href="{{ route('servicios.index') }}">
                            <i class="fas fa-concierge-bell"></i> <span>Servicios</span>
                        </a>
                    </li>

                    <!-- Tareas -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('tareas*') ? 'active' : '' }}" href="{{ route('tareas.index') }}">
                            <i class="fas fa-tasks"></i> <span>Tareas</span>
                        </a>
                    </li>

                    <!-- tecnicos -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('tecnicos*') ? 'active' : '' }}" href="{{ route('tecnicos.index') }}">
                            <i class="fas fa-toolbox"></i> <span>Tecnicos</span>
                        </a>
                    </li>

                    <!-- Repuestos -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('repuestos*') ? 'active' : '' }}"
                            href="{{ route('repuestos.index') }}">
                            <i class="fas fa-tools"></i> <span>Repuestos</span>
                        </a>
                    </li>

                    <!-- Modelos -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('modelos*') ? 'active' : '' }}" href="{{ route('modelos.index') }}" title="modelo de equipos o Dispositivos">
                            <i class="fas fa-desktop"></i> <span>Modelos</span>
                        </a>
                    </li>
                    
                    <!-- MMarca -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('marcas*') ? 'active' : '' }}" href="{{ route('marcas.index') }}" title="Marcas">
                            <i class="fas fa-briefcase"></i> <span>Marcas</span>
                        </a>
                    </li>
                    
                    <!-- Dispositivos -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dispositivos*') ? 'active' : '' }}"
                            href="{{ route('dispositivos.index') }}" title="Dispositivos de Sucursales">
                            <i class="fas fa-laptop"></i> <span>Dispositivos</span>
                        </a>
                    </li>

                    <!-- parametros -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('parametros*') ? 'active' : '' }}"
                            href="{{ route('parametros.index') }}" title="parametros para clasificar un equipo o Dispositivos y mas">
                            <i class="fas fa-cogs"></i> <span>Parametros</span>
                        </a>
                    </li>

                    <!-- Roles -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                            <i class="fas fa-user-shield"></i> <span>Roles</span>
                        </a>
                    </li>

                    <!-- Usuarios -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('usuarios*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                            <i class="fas fa-users"></i> <span>Usuarios</span>
                        </a>
                    </li>

                    <!-- Salir -->
                    <li class="nav-item">
                        <a class="nav-link logout" href="#" style="margin: 0px;"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> <span>Salir</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>

                @endif

            @else
                <span class="text-muted">Sin rol</span>
            @endif

            <!-- Para Tecnicos: Solo opciones limitadas -->
            @if (auth()->user()->roles->isNotEmpty())

                @if (auth()->user()->roles->first()->name === 'Tecnicos')

                            
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('mis-ordenes') ? 'active' : '' }}" href="{{ route('misOrdenes') }}"
                                style="display: flex; gap: 6px; align-items: center; justify-content: start">
                                <i class="fa-solid fa-clipboard" style="color: #cc0066"></i>
                                <span><strong>Mis Ordenes</strong></span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('modelos*') ? 'active' : '' }}" href="{{ route('modelos.index') }}" title="modelo de equipos o Dispositivos">
                                <i class="fas fa-desktop"></i> <span>Modelos</span>
                            </a>
                        </li>
                        
                        
                         <li class="nav-item">
                            <a class="nav-link {{ Request::is('repuestos*') ? 'active' : '' }}"
                                href="{{ route('repuestos.index') }}">
                                <i class="fas fa-tools"></i> <span>Repuestos</span>
                            </a>
                        </li>
                        {{--
                        <li class="list-item">
                            <a class="flex gap-[6px] items-center justify-start px-[20px]" href="#">
                                <i class="fa-solid fa-calendar-week" style="color: #cc0066"></i>
                                <span><strong>Agendamiento</strong></span>
                            </a>
                        </li>
                        --}}
                        <li class="nav-item">
                            <a class="nav-link logout" href="#" style="margin: 0px;"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> <span>Salir</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                       
                @endif

            @else
                <span class="text-muted">Sin rol</span>
            @endif

        </ul>

    </nav>

    

</aside>
