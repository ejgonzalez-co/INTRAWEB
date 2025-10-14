<li class="{{ Request::is('leca/ensayo-fosfato*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-fosfato.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-fosfato*') ? 'active' : '' }}">
   <a href="get-ejecutar-fosfato"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Fosfato</span></a>
</li>