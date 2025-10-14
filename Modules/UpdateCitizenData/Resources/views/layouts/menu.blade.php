<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('update-citizen-data/udc-requests*') ? 'active' : '' }}">
    <a href="{{ route('udc-requests.index') }}"><i class="fa fa-edit"></i><span>@lang('polls')</span></a>
</li>

