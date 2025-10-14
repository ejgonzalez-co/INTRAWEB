<li class="{{ Request::is('leca/ensayo-calcio*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-calcio.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-calcio*') ? 'active' : '' }}">
   <a href="get-ejecutar-calcio"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Calcio</span></a>
</li>