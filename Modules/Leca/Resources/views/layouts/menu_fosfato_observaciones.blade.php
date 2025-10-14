<li class="{{ Request::is('leca/ensayo-fosfato*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-fosfato.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-duplicados-fosfato*') ? 'active' : '' }}">
   <a href="observacion-duplicados-fosfato"><i class="fas fa-file-contract"></i><span>Observación Duplicados</span></a>
</li>