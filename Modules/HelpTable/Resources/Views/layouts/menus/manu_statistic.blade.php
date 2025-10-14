<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('help-table/statistics*') ? 'active' : '' }}">
    <a href="{{ route('tic-statistics.index') }}"><i class="fa fa-edit"></i><span>@lang('statistics')</span></a>
</li>
