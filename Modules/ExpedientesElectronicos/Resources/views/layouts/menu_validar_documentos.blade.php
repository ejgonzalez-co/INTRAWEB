<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<li class="{{ Request::is('expedientes-electronicos/validar-documento-expediente') ? 'active' : '' }}">
    <a href="{{ url('expedientes-electronicos/validar-documento-expediente') }}" class="nav-link">
        <span>Validar documentos del expediente</span>
    </a>
</li>
