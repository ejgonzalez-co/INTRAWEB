<li class="{{ Request::is('leca/ensayo-olor*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-olor.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-olor*') ? 'active' : '' }}">
   <a href="observacion-duplicados-olor"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>