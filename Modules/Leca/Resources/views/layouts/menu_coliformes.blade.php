<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-coliformes*') ? 'active' : '' }}">
   <a href="ensayo-coliformes"><i class="fas fa-file-contract"></i><span>Ejecución ensayo coliforme total</span></a>
</li>