<li class="{{ Request::is('leca/ensayo-hierro*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-hierro.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-hierro*') ? 'active' : '' }}">
   <a href="observacion-duplicados-hierro"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>