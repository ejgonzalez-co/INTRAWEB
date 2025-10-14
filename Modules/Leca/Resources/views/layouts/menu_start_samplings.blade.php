<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('leca/start-samplings*') ? 'active' : '' }}">
    <a href="{{ route('start-samplings.index') }}"><i class="fas fa-file-contract"></i><span>@lang('startSamplings')</span></a>
</li>