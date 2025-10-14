<li class="{{ Request::is('/dashboard') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('Regresar')</span></a>
</li>

<li class="{{ Request::is('leca/report-managements') ? 'active' : '' }}">
    <a href="{{ url('leca/report-managements') }}"><i class="fas fa-chart-line"></i><span>@lang('Gestión de informes')</span></a>
</li>

<li class="{{ Request::is('leca/consecutive-settings') ? 'active' : '' }}">
    <a href="{{ url('leca/consecutive-settings') }}"><i class="fas fa-cog"></i><span>@lang('Configuración consecutivos')</span></a>
</li>
