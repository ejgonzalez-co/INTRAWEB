<li class="{{ Request::is('leca/ensayo-alcalinidad*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-alcalinidad.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-alcalinidad*') ? 'active' : '' }}">
   <a href="get-ejecutar-alcalinidad"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Alcalinidad</span></a>
</li>