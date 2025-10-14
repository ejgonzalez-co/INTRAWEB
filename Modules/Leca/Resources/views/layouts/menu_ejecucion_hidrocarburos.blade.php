<li class="{{ Request::is('leca/ensayo-hidrocarburos*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-hidrocarburos.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-hidrocarburos*') ? 'active' : '' }}">
   <a href="get-ejecutar-hidrocarburos"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Hidrocarburos</span></a>
</li>