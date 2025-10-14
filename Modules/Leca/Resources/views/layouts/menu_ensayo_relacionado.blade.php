<li class="{{ Request::is('leca/list-ensayos-rutina*') ? 'active' : '' }}">
   <a href="list-ensayos-rutina"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="list-ensayos-relacionados"><i class="fas fa-file-contract"></i><span>Ensayos relacionados</span></a>
</li>