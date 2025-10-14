<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('leca/sample-receptions*') ? 'active' : '' }}">
    <a href="sample-receptions"><i class="fas fa-file-contract"></i><span>Recepción de muestra</span></a>
</li>