{{-- 
<li class="{{ Request::is('/components?module=leca') ? 'active' : '' }}">
    <a href="{{ url('/login-costumer') }}"><i class="fa fa-arrow-left"></i><span>@lang('Salir de sesion')</span></a>
</li> --}}

<li class="{{ Request::is('leca/inform-customer-attecheds') ? 'active' : '' }}">
    <a href="{{ url('leca/inform-customer-attecheds?report_id='.$_GET["report_id"]) }}"><i class="fa fa-paperclip"></i><span>@lang('Informe')</span></a>
</li>
