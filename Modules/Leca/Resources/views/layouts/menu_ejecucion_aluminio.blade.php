<li class="{{ Request::is('leca/ensayo-aluminio*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-aluminio.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-aluminio*') ? 'active' : '' }}">
   <a href="get-ejecutar-aluminio"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Aluminio</span></a>
</li>