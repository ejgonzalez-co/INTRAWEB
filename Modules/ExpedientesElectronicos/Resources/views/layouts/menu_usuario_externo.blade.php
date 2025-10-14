<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/logout-usuarios-externos-expedientes') }}" class="nav-link">
        <i class="nav-icon fas fa-power-off"></i>
        <span>@lang('Salir')</span>
    </a>
</li>

<li class="{{ Request::is('expedientes-electronicos/usuarios-externo*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('indexUsuarioExterno') }}" style="width: 90%;">
        <i class="nav-icon fas fa-file-alt"></i>
        <span>Expedientes electrónicos</span>
    </a>
</li>
