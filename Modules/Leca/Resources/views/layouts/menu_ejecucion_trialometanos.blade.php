<li class="{{ Request::is('leca/ensayo-trialometanos*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-trialometanos.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/get-ejecutar-trialometanos*') ? 'active' : '' }}">
   <a href="get-ejecutar-trialometanos"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Trialometanos</span></a>
</li>