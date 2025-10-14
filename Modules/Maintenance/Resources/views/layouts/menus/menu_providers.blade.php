<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('maintenance/providers*') ? 'active' : '' }}">
    <a href="{{ route('providers.index') }}"><i class="fa fa-id-badge"></i><span>@lang('providers')</span></a>
</li>