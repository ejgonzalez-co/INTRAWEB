<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-calcio*') ? 'active' : '' }}">
   <a href="ensayo-calcio"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Calcio</span></a>
</li>