<li class="{{ Request::is('leca/ensayo-hierro*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-hierro.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-hierro*') ? 'active' : '' }}">
   <a href="get-ejecutar-hierro"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Hierro</span></a>
</li>