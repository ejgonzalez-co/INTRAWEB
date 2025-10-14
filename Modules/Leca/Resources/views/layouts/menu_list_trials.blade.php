<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('leca/list-trials*') ? 'active' : '' }}">
    <a href="{{ route('list-trials.index') }}"><i class="fa fa-edit"></i><span>@lang('listTrials')</span></a>
</li>