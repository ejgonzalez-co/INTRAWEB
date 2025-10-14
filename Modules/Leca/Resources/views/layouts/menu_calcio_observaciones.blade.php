<li class="{{ Request::is('leca/ensayo-calcio*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-calcio.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-calcio*') ? 'active' : '' }}">
   <a href="observacion-duplicados-calcio"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>