<li class="{{ Request::is('leca/ensayo-disueltos*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-disueltos.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-disueltos*') ? 'active' : '' }}">
   <a href="observacion-duplicados-disueltos"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>