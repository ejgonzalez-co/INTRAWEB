<li class="{{ Request::is('leca/ensayo-nitrito*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-nitrito.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-nitrito*') ? 'active' : '' }}">
   <a href="observacion-duplicados-nitrito"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>