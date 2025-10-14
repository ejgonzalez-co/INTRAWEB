<!-- begin #header -->
@php
$ultima_configuracion = DB::table('configuration_general')->latest()->first();

if(Auth::check()){

   if(Auth::user()->hasRole('Administrador intranet')){
        $novedades_notificaciones = DB::table('notificaciones_mail_intraweb')
        ->where('estado_notificacion', 'Rebote')
        ->orWhere('estado_notificacion', 'No enviado')
        ->count();
   }
    else{
        $novedades_notificaciones = DB::table('notificaciones_mail_intraweb')->where(
        function($q){
            $q->where('estado_notificacion', 'Rebote');
            $q->orWhere('estado_notificacion', 'No enviado');
        })
        ->where('user_id',Auth::user()->id)
        ->count();
    }
}else{
    $novedades_notificaciones = 0;
}


@endphp
<div id="header" class="header navbar-default" style="background-color: {{ $ultima_configuracion->color_barra }}">
    <!-- begin navbar-header -->
    <div class="navbar-header">
    @if(empty($sidebarHide))
        <button type="button" class="navbar-toggle collapsed navbar-toggle-left text-white" data-click="sidebar-minify">
            <i class="fa fa-bars"></i>
        </button>
        <button type="button" class="navbar-toggle text-white" data-click="sidebar-toggled">
            <i class="fa fa-bars"></i>
        </button>
    @else
        <button onclick="window.location='/dashboard'" type="button" class="navbar-toggle collapsed navbar-toggle-left text-white">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button onclick="window.location='/dashboard'" type="button" class="navbar-toggle text-white  pl-3">
            <i class="fa fa-chevron-left"></i>
        </button>


    @endif
        <a href="/dashboard" class="navbar-brand text-white mr-0">
            {{ config('app.name') }}
        </a>

        {{-- <img src="{{ asset('assets/img/logo_ceropapeles.png') }}" style="width: 120px;" alt="" /> --}}
        <img src="{{ asset('storage')}}/{{ $ultima_configuracion->logo }}" style="max-height: 60px; width: 90px;" alt="" />

    </div>

    <!-- end navbar-header --><!-- begin header-nav -->
    <div class="navbar-nav navbar-right" id="buscador_global" style="width: auto; padding: 0; display: flex; justify-content: flex-end; ">
        {{-- Buscador universal --}}
        <search-universal query="buscador" v-if="{{ Request::is('intranet*public') || Request::is('dashboard') ? 1 : 0 }}" style="width: 100%; "></search-universal>
    </div>
    <!-- end navbar-header --><!-- begin header-nav -->
    <ul id="nav_ayudas" class="{{ Request::is('intranet*public') || Request::is('dashboard') ? 'navbar-nav' : 'navbar-nav navbar-right' }}">

    {{-- @if(Auth::check() && !Auth::user()->hasRole('Ciudadano') &&  (Auth::user()->hasRole('Operador Expedientes Electrónicos') || Auth::user()->hasRole('Consulta Expedientes Electrónicos')))
        <a href="{{ url('/expedientes-electronicos/expediente-historials') }}" style="margin: auto; margin-right: 15px; color: white; position: relative;" title="Novedades expedientes electronicos">
            <i class="fas fa-bell" aria-hidden="true" style="font-size: 1.33333em;"></i>
        </a>
    @endif --}}

    @if(Auth::check() && !Auth::user()->hasRole('Ciudadano') && !Auth::user()->hasRole('Proveedor TIC'))
        <a href="{{ url('/notificaciones-mail-intrawebs') }}" style="margin: auto; margin-right: 15px; color: white; position: relative;" title="Novedades de envio de correos">
            <i class="fas fa-envelope" aria-hidden="true" style="font-size: 1.33333em;"></i>
            @if($novedades_notificaciones > 0)
                <span class="badge badge-danger position-absolute" style="bottom: -4px; right: -10px; font-size: 10px; padding: 3px 5px; border-radius: 50%;">
                    {{$novedades_notificaciones}}
                </span>
            @endif
        </a>
    @endif

        {{-- Ícono de ayuda general --}}
        <a href="{{ url('/configuracion/documentos-ayudas-publico') }}" style="margin: auto; margin-right: 0; color: white;" title="Ayuda">
            <i class="fa fa-question-circle fa-lg" aria-hidden="true"></i>
        </a>
        <li class="dropdown navbar-user" style="display: inline-flex;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="d-md-inline text-white">Bienvenido, {{ Auth::user() ? Auth::user()->name : (session('name') ? session('name') : 'Usuario(a)') }}</span>
            @if(!empty(Auth::user()->url_img_profile) && Auth::user()->url_img_profile != "users/avatar/default.png")
                <img src="{{ asset('storage')}}/{{ Auth::user()->url_img_profile }}" alt="" />
            @else
                <img src="{{ asset('assets/img/user/profile.png')}}" alt="" />
            @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                {{-- Si es un ciudadano, no se le muestra el item de editar perfil --}}
                @if(Auth::check() && !Auth::user()->hasRole('Ciudadano') && !Auth::user()->hasRole('Proveedor TIC'))
                    <a href="{{ url('/profile') }}" class="dropdown-item">@lang('edit_profle')</a>
                    <div class="dropdown-divider"></div>
                @endif
                {{-- Valida si tiene sesión el usuario para habilitar la acción de cerrar sesión --}}
                @if(Auth::check())
                    <a href="{{ url('/logout') }}" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        @lang('Logout')
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endif
            </div>
        </li>
    </ul>
    <!-- end header navigation right -->
</div>
<!-- end #header -->
