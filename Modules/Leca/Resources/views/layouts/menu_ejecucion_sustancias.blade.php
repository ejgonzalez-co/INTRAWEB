<li class="{{ Request::is('leca/ensayo-sustancias*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-sustancias.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-sustancias*') ? 'active' : '' }}">
   <a href="get-ejecutar-sustancias"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Sustancias flotantes</span></a>
</li>