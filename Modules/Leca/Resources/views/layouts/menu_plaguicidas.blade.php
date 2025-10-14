<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-plaguicidas*') ? 'active' : '' }}">
   <a href="ensayo-plaguicidas"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Plaguicidas</span></a>
</li>