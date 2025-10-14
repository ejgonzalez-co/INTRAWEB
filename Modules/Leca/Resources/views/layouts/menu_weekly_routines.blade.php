<li class="{{ Request::is('leca/sampling-schedules*') ? 'active' : '' }}">
    <a href="{{ route('sampling-schedules.index') }}"><i class="fas fa-calendar-alt"></i><span>@lang('programming')</span></a>
</li>
<li class="{{ Request::is('leca/monthly-routines*') ? 'active' : '' }}">
    <a href="{{ route('monthly-routines.index') }}"><i class="fas fa-network-wired"></i><span>@lang('monthlyRoutines')</span></a>
</li>
<li class="{{ Request::is('leca/weekly-routines*') ? 'active' : '' }}">
    <a href="{{ route('weekly-routines.index') }}"><i class="fas fa-business-time"></i><span>@lang('weeklyRoutines')</span></a>
</li>