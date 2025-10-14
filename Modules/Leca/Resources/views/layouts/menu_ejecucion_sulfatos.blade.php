<li class="{{ Request::is('leca/ensayo-sulfatos*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-sulfatos.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-sulfatos*') ? 'active' : '' }}">
   <a href="get-ejecutar-sulfatos"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Sulfatos</span></a>
</li>