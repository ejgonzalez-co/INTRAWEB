<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ route('tic-requests.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('help-table/data-analytics*') ? 'active' : '' }}">
    <a href="{{ route('data-analytics.index') }}"><i class="fas fa-chart-bar"></i><span>@lang('Analitíca de datos')</span></a>
</li>