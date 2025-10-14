<li class="{{ Request::is('help-table/equipment-resumes*') ? 'active' : '' }}">
    <a href="{{ route('equipment-resumes.index') }}"><i class="fas fa-desktop"></i><span>@lang('Hoja de vida de los equipos')</span></a>
</li>

<li class="{{ Request::is('help-table/config-towers*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-towers') }}"><i class="fas fa-cog"></i><span> @lang('Configuración Torres')</span></a>
</li>
{{-- <li class="{{ Request::is('help-table/config-keyboards*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-keyboards') }}"><i class="fas fa-cog"></i><span> @lang('Configuración Teclados')</span></a>
</li>
<li class="{{ Request::is('help-table/config-mouses*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-mouses') }}"><i class="fas fa-cog"></i><span> @lang('Configuración Mouses')</span></a>
</li> --}}
<li class="{{ Request::is('help-table/config-monitors*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-monitors') }}"><i class="fas fa-cog"></i><span> @lang('Configuración Monitores')</span></a>
</li>