<li class="{{ Request::is('leca/ensayo-carbono*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-carbono.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-carbono*') ? 'active' : '' }}">
   <a href="get-ejecutar-carbono"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Carbono</span></a>
</li>