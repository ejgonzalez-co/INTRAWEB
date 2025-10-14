<li class="{{ Request::is('leca/ensayo-dureza*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-dureza.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-dureza*') ? 'active' : '' }}">
   <a href="observacion-duplicados-dureza"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>