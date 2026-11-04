<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img src="{{ asset('assets/images/logo-principal-white.png') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pe-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                    </div>
                    <input type="text" placeholder="Search ..." class="form-control" />
                </div>
            </nav>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false" aria-haspopup="true">
                        <i class="fa fa-search"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-search animated fadeIn">
                        <form class="navbar-left navbar-form nav-search">
                            <div class="input-group">
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </form>
                    </ul>
                </li>

                <li class="nav-item topbar-icon hidden-caret">
                    <a class="nav-link" href="#" id="themeToggle" role="button" aria-haspopup="true"
                        aria-expanded="false" title="Cambiar tema">
                        <i class="fa fa-moon"></i>
                    </a>
                </li>

                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        @if($ventasPendientesCierreHoy > 0)
                        <span class="notification">{{ $ventasPendientesCierreHoy > 99 ? '99+' : $ventasPendientesCierreHoy }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">
                                @if($ventasPendientesCierreHoy > 0)
                                    {{ $ventasPendientesCierreHoy }} {{ $ventasPendientesCierreHoy === 1 ? 'venta pendiente' : 'ventas pendientes' }} por cerrar hoy
                                @else
                                    No tienes ventas pendientes por cerrar hoy
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="notif-scroll scrollbar-outer">
                                <div class="notif-center">
                                    @if($ventasPendientesCierreHoy > 0)
                                    <a href="{{ route('admin.ventas.cerrar', ['fecha_desde' => $fechaCierreHoy, 'fecha_hasta' => $fechaCierreHoy]) }}">
                                        <div class="notif-icon notif-warning">
                                            <i class="fa fa-cash-register"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block">
                                                {{ $ventasPendientesCierreHoy }} {{ $ventasPendientesCierreHoy === 1 ? 'venta pendiente' : 'ventas pendientes' }} de hoy
                                            </span>
                                            <span class="time">Ver cierre de ventas</span>
                                        </div>
                                    </a>
                                    @else
                                    <div class="text-center text-muted py-3">
                                        Todo al día
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="{{ route('admin.ventas.cerrar', ['fecha_desde' => $fechaCierreHoy, 'fecha_hasta' => $fechaCierreHoy]) }}">Ir al cierre de ventas<i
                                    class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm d-flex align-items-center justify-content-center rounded-circle bg-primary">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hola,</span>
                            <span class="fw-bold">
                                {{ Auth::check() ? Auth::user()->nombres : 'Invitado' }}
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user fa-3x"></i>
                                    </div>
                                    <div class="u-text">
                                        @if(Auth::check())
                                        <h4>{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                        <span
                                            class="badge badge-primary">{{ Auth::user()->rol->nombre ?? 'Sin Rol' }}</span>
                                        @else
                                        <h4>Usuario no identificado</h4>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Mi Perfil</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Ajustes de Cuenta</a>
                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Cerrar Sesión
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>