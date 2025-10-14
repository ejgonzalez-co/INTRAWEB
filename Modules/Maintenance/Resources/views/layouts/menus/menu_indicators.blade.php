<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('maintenance/indicators-index') ? 'active' : '' }}">
   <a href="{!! url('maintenance/indicators-index') !!}"><i class="fa fa-chart-line"></i><span>Indicadores</span></a>
</li>