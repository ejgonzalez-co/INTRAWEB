<li class="{{ Request::is('leca/ensayo-aluminio*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-aluminio.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-aluminio*') ? 'active' : '' }}">
   <a href="observacion-duplicados-aluminio"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>