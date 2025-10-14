<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ route('equipment-resumes.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('help-table/equipment-resumes*') ? 'active' : '' }}">
    <a href="{{ route('equipment-resumes.index') }}"><i class="fas fa-desktop"></i><span>@lang('Hoja de vida de los equipos')</span></a>
</li>