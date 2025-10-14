<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('maintenance/budget-provider*') ? 'active' : '' }}">
   <a href="{!! url('maintenance/budget-provider') !!}"><i class="fa fa-calculator"></i><span>Presupuesto</span></a>
</li>