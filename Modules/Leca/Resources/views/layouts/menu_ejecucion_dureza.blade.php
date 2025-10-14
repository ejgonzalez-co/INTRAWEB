<li class="{{ Request::is('leca/ensayo-dureza*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-dureza.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-dureza*') ? 'active' : '' }}">
   <a href="get-ejecutar-dureza"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Dureza</span></a>
</li>