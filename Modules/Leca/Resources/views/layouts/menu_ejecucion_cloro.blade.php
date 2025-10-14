<li class="{{ Request::is('leca/ensayo-cloro*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-cloro.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-cloro*') ? 'active' : '' }}">
   <a href="get-ejecutar-cloro"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Cloro</span></a>
</li>