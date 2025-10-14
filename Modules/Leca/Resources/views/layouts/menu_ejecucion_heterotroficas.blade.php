<li class="{{ Request::is('leca/ensayo-heterotroficas*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-heterotroficas.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-heterotroficas*') ? 'active' : '' }}">
   <a href="get-ejecutar-heterotroficas"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Heterotroficas</span></a>
</li>