<li class="{{ Request::is('leca/ensayo-nitrato*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-nitrato.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-nitrato*') ? 'active' : '' }}">
   <a href="observacion-duplicados-nitrato"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>