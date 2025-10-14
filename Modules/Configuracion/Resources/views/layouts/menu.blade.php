<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

{{-- Los siguientes accesos solo puede acceder un usuario que tenga el rol Administrador intranet --}}
@role('Administrador intranet')
    <li class="{{ Request::is('configuracion/configuration-generals*') ? 'active' : '' }}">
        <a href="{{ route('configuration-generals.index') }}">
            <i class="fa fa-cog"></i>
            <span>Configuración general</span>
        </a>
    </li>

    @role('Admin seven')
    <li class="{{ Request::is('configuracion/variables*') ? 'active' : '' }}">
        <a href="{{ route('variables.index') }}">
            <i class="fa fa-check"></i>
            <span>@lang('Variables')</span>
        </a>
    </li>
    @endrole

    <li class="nav-item {{ Request::is('configuracion/documentos-ayudas') ? 'active' : '' }}">
        <a href="{{ route('documentos-ayudas.index') }}" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt {"></i>
            <span>Documentos de ayuda</span>
        </a>
    </li>
@endrole

<li class="nav-item {{ Request::is('configuracion/documentos-ayudas-publico*') ? 'active' : '' }}">
    <a href="{{ url('configuracion/documentos-ayudas-publico') }}" class="nav-link">
        <i class="nav-icon fas fa-file-alt"></i>
        <span>Documentos de ayuda públicos</span>
    </a>
</li>

@role('Admin seven')
    <li class="nav-item {{ Request::is('configuracion/versions-updates') ? 'active' : '' }}">
        <a href="{{ route('versions-updates.index') }}" class="nav-link">
            <i class="fas fa-sort-amount-up-alt"></i>
            <span>Versiones de Intraweb</span>
        </a>
    </li>
@endrole
