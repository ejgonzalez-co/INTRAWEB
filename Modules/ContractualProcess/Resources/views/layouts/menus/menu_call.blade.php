<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

@if(Auth::user()->hasRole('PC Gestor de recursos'))
<li class="{{ Request::is('process-leaders*') ? 'active' : '' }}">
    <a href="{{ route('process-leaders.index') }}"><i class="fa fa-edit"></i><span>@lang('Process Leaders')</span></a>
</li>
@endif

<li class="{{ Request::is('paa-calls*') ? 'active' : '' }}">
    <a href="{{ route('paa-calls.index') }}"><i class="fa fa-edit"></i><span>@lang('Paa Calls')</span></a>
</li>

