@if(Auth::check())
<li class="nav-item {{ Request::is('expedientes-electronicos/expedientes*') ? 'active' : '' }}">
   <a href="{{ route('expedientes.index') }}" class="nav-link">
       <i class="nav-icon fas fa-arrow-left"></i>
       <span>@lang('back')</span>
   </a>
</li>
@elseif(!Auth::check() && session('pin_acceso'))
    <li class="nav-item {{ Request::is('expedientes-electronicos/usuarios-externos*') ? 'active' : '' }}">
        <a href="{{ route('indexUsuarioExterno') }}" class="nav-link">
            <i class="nav-icon fas fa-arrow-left"></i>
            <span>@lang('back')</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
        <a href="{{ url('/logout-usuarios-externos-expedientes') }}" class="nav-link">
            <i class="nav-icon fas fa-power-off"></i>
            <span>@lang('Salir')</span>
        </a>
    </li>
@endif
{{-- <li class="nav-item {{ Request::is('expedientes-electronicos/documentos-expedientes*') ? 'active' : '' }}">
   <a href="{{ route('documentos-expedientes.index') }}" class="nav-link">
       <i class="fa fa-file-alt"></i>
       <p>Documentos Expedientes</p>
   </a>
</li> --}}