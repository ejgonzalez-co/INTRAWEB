<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('maintenance/tire-informations*') ? 'active' : '' }}">
    <a href="{{ route('tire-informations.index') }}"><i class="fas fa-truck-pickup"></i></i><span>@lang('tireQuantitites')</span></a>
</li>
