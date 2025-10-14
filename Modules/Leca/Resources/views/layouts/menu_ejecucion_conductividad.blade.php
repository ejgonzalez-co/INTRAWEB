<li class="{{ Request::is('leca/ensayo-conductividad*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-conductividad.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-conductividad*') ? 'active' : '' }}">
   <a href="get-ejecutar-conductividad"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Conductividad</span></a>
</li>