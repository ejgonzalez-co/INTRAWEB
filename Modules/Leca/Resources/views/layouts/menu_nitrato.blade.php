<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-nitrato*') ? 'active' : '' }}">
   <a href="ensayo-nitrato"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Nitrato</span></a>
</li>