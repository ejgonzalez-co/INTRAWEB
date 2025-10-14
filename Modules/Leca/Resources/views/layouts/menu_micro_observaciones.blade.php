<li class="{{ Request::is('leca/ensayo-'. $ensayo .'*') ? 'active' : '' }}">
   <a href="{{ route('ensayo-'. $ensayo .'.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('leca/observacion-'. $ensayo .'*') ? 'active' : '' }}">
   <a href="observacion-@php echo  $ensayo @endphp"><i class="fas fa-file-contract"></i><span>Observación {{$ensayo}}</span></a>
</li>