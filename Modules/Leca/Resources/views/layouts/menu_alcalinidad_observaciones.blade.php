<li class="{{ Request::is('leca/ensayo-alcalinidad*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-alcalinidad.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-alcalinidad*') ? 'active' : '' }}">
   <a href="observacion-alcalinidad"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>