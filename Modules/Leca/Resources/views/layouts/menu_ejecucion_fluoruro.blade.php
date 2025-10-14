<li class="{{ Request::is('leca/ensayo-fluoruro*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-fluoruro.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-fluoruro*') ? 'active' : '' }}">
   <a href="get-ejecutar-fluoruro"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Fluoruro</span></a>
</li>