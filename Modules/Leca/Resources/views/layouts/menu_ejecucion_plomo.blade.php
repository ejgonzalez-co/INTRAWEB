<li class="{{ Request::is('leca/ensayo-plomo*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-plomo.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-plomo*') ? 'active' : '' }}">
   <a href="get-ejecutar-plomo"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Plomo</span></a>
</li>