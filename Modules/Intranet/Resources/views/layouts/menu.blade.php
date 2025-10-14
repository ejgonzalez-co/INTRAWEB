<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('intranet/users*') ? 'active' : '' }}">
    <a href="{{ route('users.index') }}"><i class="fa fa-user"></i><span>@lang('officers')</span></a>
</li>

<li class="{{ Request::is('intranet/dependencies*') ? 'active' : '' }}">
    <a href="{{ url('/intranet/dependencies') }}"><i class="fa fa-network-wired"></i><span>@lang('dependencies')</span></a>
</li>

<li class="{{ Request::is('intranet/work-groups*') ? 'active' : '' }}">
    <a href="{{ route('work-groups.index') }}"><i class="fa fa-users"></i><span>@lang('work_groups')</span></a>
</li>

<li class="{{ Request::is('intranet/positions*') ? 'active' : '' }}">
    <a href="{{ url('/intranet/positions') }}"><i class="fa fa-address-book"></i><span>@lang('positions')</span></a>
</li>

<li class="{{ Request::is('intranet/headquarters*') ? 'active' : '' }}">
    <a href="{{ route('headquarters.index') }}"><i class="fa fa-industry"></i><span>@lang('headquarters')</span></a>
</li>

<li class="{{ Request::is('intranet/citizens*') ? 'active' : '' }}">
    <a href="{{ route('citizens.index') }}"><i class=" fas fa-users"></i><span>@lang('Citizens')</span></a>
</li>
