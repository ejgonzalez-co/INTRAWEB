<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/login') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<li class="nav-item {{ Request::is('pqrs/p-q-r-s-ciudadano-anonimo') ? 'active' : '' }}">
    <a href="{{ route('p-q-r-s-ciudadano-anonimo.index') }}" class="nav-link">
        <i class="nav-icon fas fa-bullhorn"></i>
        <span>PQRS</span>
    </a>
</li>

{{-- Valida si tiene la integración con un sitio de Joomla habilitada --}}
@if (config("app.integracion_sitio_joomla"))
    {{-- <li class="nav-item {{ Request::is('pqrs/p-q-r-s-ciudadano-anonimo-repository*') ? 'active' : '' }}">
        <a href="{{ url('/pqrs/p-q-r-s-ciudadano-anonimo-repository') }}" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <span>@lang('Repositorio de PQRS')</span>
        </a>
    </li> --}}
@endif