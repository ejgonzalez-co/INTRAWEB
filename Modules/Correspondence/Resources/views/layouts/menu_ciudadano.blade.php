<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/login') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<li class="nav-item {{ Request::is('correspondence/search-pqrs-ciudadano') ? 'active' : '' }}">
    <a href="{{ url('search-pqrs-ciudadano') }}" class="nav-link">
        <i class="nav-icon fas fa-bullhorn"></i>
        <span>Consulta de PQRS</span>
    </a>
</li>
