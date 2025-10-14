<li class="{{ Request::is('leca/ensayo-cloruro*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-cloruro.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-cloruro*') ? 'active' : '' }}">
   <a href="observacion-duplicados-cloruro"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>