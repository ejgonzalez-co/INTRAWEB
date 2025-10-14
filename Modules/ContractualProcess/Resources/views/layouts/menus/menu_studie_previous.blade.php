<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('contractual-process/pc-previous-studies') ? 'active' : '' }}">
    <a href="{{ route('pc-previous-studies.index') }}"><i class="fa fa-edit"></i><span>@lang('Studies') @lang('Previous')</span></a>
</li>

@if(Auth::user()->hasRole('PC Gestor de recursos') )
<li class="{{ Request::is('process-leaders*') ? 'active' : '' }}">
    <a href="{{ route('process-leaders.index') }}"><i class="fa fa-edit"></i><span>@lang('Process Leaders')</span></a>
</li>
@endif

@if(Auth::user()->hasRole('PC Asistente de gerencia'))

<li class="{{ Request::is('contractual-process/pc-previous-studies-radications*') ? 'active' : '' }}">
    <a href="{{ route('pc-previous-studies-radications.index') }}"><i class="fa fa-file-invoice"></i><span>@lang('Studies') @lang('Previous') @lang('radicados')</span></a>
</li>

@endif
