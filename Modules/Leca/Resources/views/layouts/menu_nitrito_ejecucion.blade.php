<li class="{{ Request::is('leca/ensayo-nitrito*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-nitrito.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('leca/get-ejecutar-nitrito*') ? 'active' : '' }}">
   <a href="'get-ejecutar-nitrito"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Nitrito</span></a>
</li>