<li class="{{ Request::is('leca/ensayo-cloro*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-cloro.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-cloro*') ? 'active' : '' }}">
   <a href="observacion-duplicados-cloro"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>