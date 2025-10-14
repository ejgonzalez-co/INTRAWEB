<li class="{{ Request::is('leca/ensayo-acidez*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-acidez.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-acidez*') ? 'active' : '' }}">
   <a href="get-ejecutar-acidez"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Acidez</span></a>
</li>