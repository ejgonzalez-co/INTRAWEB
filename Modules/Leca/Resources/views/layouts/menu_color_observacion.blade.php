<li class="{{ Request::is('leca/ensayo-color*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-color.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-color*') ? 'active' : '' }}">
   <a href="observacion-duplicados-color"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>