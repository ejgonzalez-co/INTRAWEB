<li class="{{ Request::is('leca/ensayo-ph*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-ph.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-ph*') ? 'active' : '' }}">
   <a href="get-ejecutar-ph"><i class="fas fa-file-contract"></i><span>Ejecución ensayo pH</span></a>
</li>