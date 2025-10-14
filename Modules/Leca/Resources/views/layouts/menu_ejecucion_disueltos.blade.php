<li class="{{ Request::is('leca/ensayo-disueltos*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-disueltos.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-disueltos*') ? 'active' : '' }}">
   <a href="get-ejecutar-disueltos"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Disueltos</span></a>
</li>