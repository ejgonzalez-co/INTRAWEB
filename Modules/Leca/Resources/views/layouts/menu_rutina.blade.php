<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('leca/list-ensayos-rutina*') ? 'active' : '' }}">
   <a href="list-ensayos-rutina"><i class="fas fa-file-contract"></i><span>Rutina de ensayo</span></a>
</li>