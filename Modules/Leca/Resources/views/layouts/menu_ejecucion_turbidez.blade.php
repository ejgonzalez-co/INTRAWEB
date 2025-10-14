<li class="{{ Request::is('leca/ensayo-turbidez*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-turbidez.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-turbidez*') ? 'active' : '' }}">
   <a href="get-ejecutar-turbidez"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Turbidez</span></a>
</li>