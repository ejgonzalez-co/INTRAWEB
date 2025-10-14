<li class="{{ Request::is('leca/list-ensayos-relacionados*') ? 'active' : '' }}">
   <a href="{{ route('list-ensayos-relacionados.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/ensayo-sustancias*') ? 'active' : '' }}">
   <a href="ensayo-sustancias"><i class="fas fa-file-contract"></i><span>Ejecución ensayo Sustancias Flotantes</span></a>
</li>