<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('citizen-poll/polls*') ? 'active' : '' }}">
    <a href="{!! url('/citizen-poll/polls') !!}"><i class="fa fa-edit"></i><span>@lang('polls')</span></a>
</li>
<li class="{{ Request::is('citizen-poll/image-managers*') ? 'active' : '' }}">
    <a href="{{ route('image-managers.index') }}"><i class="far fa-image"></i><span>@lang('imageAdministrator')</span></a>
</li>

