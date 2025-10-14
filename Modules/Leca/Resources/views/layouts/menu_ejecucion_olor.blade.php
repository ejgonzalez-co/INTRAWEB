<li class="{{ Request::is('leca/ensayo-olor*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-olor.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-olor*') ? 'active' : '' }}">
   <a href="get-ejecutar-olor"><i class="fas fa-file-contract"></i><span>Ejecución ensayo olor</span></a>
</li>