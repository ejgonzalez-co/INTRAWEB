<li class="{{ Request::is('leca/ensayo-ph*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-ph.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-ph*') ? 'active' : '' }}">
   <a href="observacion-duplicados-ph"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>