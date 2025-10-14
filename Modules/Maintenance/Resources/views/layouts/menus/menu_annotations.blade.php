

<li class="{{ Request::is('/maintenance/request-needs') ? 'active' : '' }}">
    <a href="{{ url('/maintenance/request-needs') }}"><i class="fas fa-long-arrow-alt-left"></i><span>Atrás</span></a>
</li>


<li class="{{ Request::is('maintenance/request-annotations*') ? 'active' : '' }}">
    <a href="{{ url('/maintenance/request-annotations?=' . $ci ) }}"><i class="fa fa-edit"></i><span>Observaciones</span></a>
</li>

