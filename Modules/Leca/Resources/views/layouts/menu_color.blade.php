<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-color*') ? 'active' : '' }}">
   <a href="ensayo-color"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Color</span></a>
</li>