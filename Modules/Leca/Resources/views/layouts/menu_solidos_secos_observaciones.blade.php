<li class="{{ Request::is('leca/ensayo-secos*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-secos.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-secos*') ? 'active' : '' }}">
   <a href="observacion-duplicados-secos"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>