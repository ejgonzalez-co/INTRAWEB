@if(Auth::user()->hasRole('Administrador Leca'))
<li class="{{ Request::is('leca/monthly-routines*') ? 'active' : '' }}">
    <a href="{{ route('monthly-routines.index') }}"><i class="fas fa-network-wired"></i><span>@lang('monthlyRoutines')</span></a>
</li>
@endif

<li class="{{ Request::is('leca/sampling-schedules*') ? 'active' : '' }}">
    <a href="{{ route('sampling-schedules.index') }}"><i class="fas fa-calendar-alt"></i><span>@lang('programming')</span></a>
</li>