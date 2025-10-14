<li class="{{ Request::is('leca/ensayo-conductividad*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-conductividad.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-dupli-conductividad*') ? 'active' : '' }}">
   <a href="observacion-dupli-conductividad"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>