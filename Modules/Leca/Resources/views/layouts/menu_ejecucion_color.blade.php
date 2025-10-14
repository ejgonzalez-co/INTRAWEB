<li class="{{ Request::is('leca/ensayo-color*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-color.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-color*') ? 'active' : '' }}">
   <a href="get-ejecutar-color"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Color</span></a>
</li>