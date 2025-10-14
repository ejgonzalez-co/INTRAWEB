<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('help-table/equipment-resumes') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('help-table/providers*') ? 'active' : '' }}">
    <a href="{{ url('help-table/providers') }}"><i class="fas fa-users"></i><span> @lang('Proveedores')</span></a>
</li>