<li class="{{ Request::is('/dashboard') ? 'active' : '' }}">
    <a href="{{ url('/login-costumer') }}"><i class="fa fa-power-off"></i><span>@lang('Salir')</span></a>
</li>

<li class="{{ Request::is('leca/inform-customers*') ? 'active' : '' }}">
    <a href="{{url('leca/inform-customers?document='.$_GET["document"]) }}"><i class="fas fa-file"></i><span>@lang('Informes')</span></a>
</li>