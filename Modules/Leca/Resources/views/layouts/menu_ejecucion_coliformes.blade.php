<li class="{{ Request::is('leca/ensayo-coliformes*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-coliformes.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-coliformes*') ? 'active' : '' }}">
   <a href="get-ejecutar-coliformes"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Coliforme total</span></a>
</li>