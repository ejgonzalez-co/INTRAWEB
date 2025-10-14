<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-disueltos*') ? 'active' : '' }}">
   <a href="ensayo-disueltos"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Disueltos</span></a>
</li>