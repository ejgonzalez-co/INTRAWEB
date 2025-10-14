<li class="{{ Request::is('leca/ensayo-plaguicidas*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-plaguicidas.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-plaguicidas*') ? 'active' : '' }}">
   <a href="get-ejecutar-plaguicidas"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Plaguicidas</span></a>
</li>