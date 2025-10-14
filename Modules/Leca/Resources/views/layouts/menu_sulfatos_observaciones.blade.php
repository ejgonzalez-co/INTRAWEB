<li class="{{ Request::is('leca/ensayo-sulfatos*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-sulfatos.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-sulfatos*') ? 'active' : '' }}">
   <a href="observacion-duplicados-sulfatos"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>