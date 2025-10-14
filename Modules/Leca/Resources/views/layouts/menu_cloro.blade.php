<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-cloro*') ? 'active' : '' }}">
   <a href="ensayo-cloro"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Cloro</span></a>
</li>