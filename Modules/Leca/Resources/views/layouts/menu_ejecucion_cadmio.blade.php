<li class="{{ Request::is('leca/ensayo-cadmio*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-cadmio.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-cadmio*') ? 'active' : '' }}">
   <a href="get-ejecutar-cadmio"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Cadmio</span></a>
</li>