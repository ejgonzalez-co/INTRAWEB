<li class="{{ Request::is('leca/ensayo-carbono*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-carbono.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-carbono*') ? 'active' : '' }}">
   <a href="observacion-duplicados-carbono"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>