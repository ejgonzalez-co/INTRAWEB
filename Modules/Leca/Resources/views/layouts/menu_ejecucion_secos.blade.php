<li class="{{ Request::is('leca/ensayo-secos*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-secos.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-secos*') ? 'active' : '' }}">
   <a href="get-ejecutar-secos"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Secos</span></a>
</li>