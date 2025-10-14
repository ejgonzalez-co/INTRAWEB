<li class="{{ Request::is('leca/ensayo-fluoruro*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-fluoruro.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-fluoruro*') ? 'active' : '' }}">
   <a href="observacion-duplicados-fluoruro"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>