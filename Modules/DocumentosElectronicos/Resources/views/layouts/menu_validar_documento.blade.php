<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<li class="{{ Request::is('documentos-electronicos/validar-documento-electronico') ? 'active' : '' }}">
    <a href="{{ url('documentos-electronicos/validar-documento-electronico') }}" class="nav-link">
        <span>Validar documento electrónico</span>
    </a>
</li>