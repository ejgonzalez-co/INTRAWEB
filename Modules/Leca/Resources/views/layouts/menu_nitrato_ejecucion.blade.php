<li class="{{ Request::is('leca/ensayo-nitrato*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-nitrato.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('leca/get-ejecutar-nitrato*') ? 'active' : '' }}">
   <a href="get-ejecutar-nitrato"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Nitrato</span></a>
</li>