<li class="{{ Request::is('leca/ensayo-mercurio*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-mercurio.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-mercurio*') ? 'active' : '' }}">
   <a href="get-ejecutar-mercurio"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Mercurio</span></a>
</li>