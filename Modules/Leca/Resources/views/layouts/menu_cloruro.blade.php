<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-cloruro*') ? 'active' : '' }}">
   <a href="ensayo-cloruro"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Cloruro</span></a>
</li>