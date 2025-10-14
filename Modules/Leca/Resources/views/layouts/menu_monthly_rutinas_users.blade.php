<li class="{{ Request::is('leca/monthly-routines-officials*') ? 'active' : '' }}">
    <a href="{{ route('monthly-routines-officials.index') }}"><i class="fas fa-user-shield"></i><span>@lang('monthlyRoutinesHasUsers')</span></a>
</li>

<li class="{{ Request::is('monthly-routines*') ? 'active' : '' }}">
    <a href="{{ route('monthly-routines.index') }}"><i class="fas fa-network-wired"></i><span>@lang('monthlyRoutines')</span></a>
</li>