<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-mercurio*') ? 'active' : '' }}">
   <a href="ensayo-mercurio"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Mercurio</span></a>
</li>