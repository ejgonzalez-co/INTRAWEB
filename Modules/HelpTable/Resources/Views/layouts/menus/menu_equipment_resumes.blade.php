<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('help-table/equipment-resumes*') ? 'active' : '' }}">
    <a href="{{ route('equipment-resumes.index') }}"><i class="fas fa-desktop"></i><span>@lang('Hoja de vida de los equipos')</span></a>
</li>

<li class="{{ Request::is('help-table/config-towers*') ? 'active' : '' }}">
    <a href="{{ route('config-towers.index') }}"><i class="fas fa-cog"></i><span>@lang('Configuraciones')</span></a>
</li>

<li class="{{ Request::is('help-table/equipment-purchase-details*') ? 'active' : '' }}">
    <a href="{{ route('equipment-purchase-details.index') }}"><i class="fas fa-cog"></i><span>@lang('Contratos compras de equipos')</span></a>
</li>
{{-- @if(Auth::user()->hasRole("Administrador TIC"))
    <li class="{{ Request::is('help-table/providers*') ? 'active' : '' }}">
        <a href="{!! url('help-table/providers') !!}"><i class="fas fa-users"></i><span>@lang('Gestión de proveedores')</span></a>
    </li>
@endif --}}
