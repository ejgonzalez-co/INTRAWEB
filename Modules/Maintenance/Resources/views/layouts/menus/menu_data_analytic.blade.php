<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('maintenance/data-analytics') ? 'active' : '' }}">
    <a href="{{ url('maintenance/data-analytics') }}"><i class="fas fa-chart-line"></i><span>@lang('Analitica de datos')</span></a>
</li> 

