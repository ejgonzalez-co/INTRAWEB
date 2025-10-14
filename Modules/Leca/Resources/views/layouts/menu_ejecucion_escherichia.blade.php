<li class="{{ Request::is('leca/ensayo-escherichia*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-escherichia.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-escherichia*') ? 'active' : '' }}">
   <a href="get-ejecutar-escherichia"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Escherichia Coli</span></a>
</li>