<li class="{{ Request::is('leca/ensayo-cloruro*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-cloruro.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-cloruro*') ? 'active' : '' }}">
   <a href="get-ejecutar-cloruro"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Cloruro</span></a>
</li>