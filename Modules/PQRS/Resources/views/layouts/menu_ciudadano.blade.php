<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/modules') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<li class="nav-item {{ Request::is('pqrs/p-q-r-s-ciudadano') ? 'active' : '' }}">
    <a href="{{ route('p-q-r-s-ciudadano') }}" class="nav-link">
        <i class="nav-icon fas fa-bullhorn"></i>
        <span>PQRS</span>
    </a>
</li>

{{-- Valida si tiene la integración con un sitio de Joomla habilitada, además, si tiene username es un usuario antiguo que requiere integración --}}
@if (config("app.integracion_sitio_joomla") && Auth::user()->username)
    <li class="nav-item {{ Request::is('pqrs/p-q-r-s-ciudadano-repository*') ? 'active' : '' }}">
        <a href="{{ url('/pqrs/p-q-r-s-ciudadano-repository') }}" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <span>@lang('Repositorio de PQRS')</span>
        </a>
    </li>
@endif
