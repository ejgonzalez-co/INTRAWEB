<li class="{{ Request::is('leca/ensayo-acidez*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-acidez.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-acidez*') ? 'active' : '' }}">
   <a href="observacion-duplicados-acidez"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>