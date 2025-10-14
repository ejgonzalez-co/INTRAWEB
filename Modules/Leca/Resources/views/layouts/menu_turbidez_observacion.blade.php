<li class="{{ Request::is('leca/ensayo-turbidez*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-turbidez.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-turbidez*') ? 'active' : '' }}">
   <a href="observacion-duplicados-turbidez"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>