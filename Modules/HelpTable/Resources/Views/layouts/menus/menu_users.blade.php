<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('help-table/users*') ? 'active' : '' }}">
    <a href="{{ url('help-table/users') }}"><i class="fa fa-user"></i><span> @lang('Technicians')</span></a>
</li>

<li class="{{ Request::is('help-table/tic-providers*') ? 'active' : '' }}">
    <a href="{{ route('tic-providers.index') }}"><i class="fas fa-id-badge"></i><span> @lang('Tic Providers')</span></a>
</li>
