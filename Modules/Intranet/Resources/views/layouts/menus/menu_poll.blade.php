<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/intranet/notices') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('intranet/polls*') ? 'active' : '' }}">
    <a href="{{ route('polls.index') }}"><i class="fa fa-edit"></i><span>@lang('polls')</span></a>
</li>