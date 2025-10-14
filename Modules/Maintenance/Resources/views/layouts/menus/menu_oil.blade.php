<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('maintenance/oil*') ? 'active' : '' }}">
    <a href="{{ route('oil.index') }}"><i class="fas fa-oil-can"></i><span> Aceite</span></a>
</li>
