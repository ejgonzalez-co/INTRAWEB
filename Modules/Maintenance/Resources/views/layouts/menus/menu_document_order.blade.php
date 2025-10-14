

<li class="{{ Request::is('/maintenance/request-needs') ? 'active' : '' }}">
    <a href="{{ url('/maintenance/request-need-orders?rn='. $rn) }}"><i class="fa fa-arrow-left"></i><span>Regresar</span></a>
</li>


<li class="{{ Request::is('maintenance/document-orders*') ? 'active' : '' }}">
    <a href="{{ url('maintenance/document-orders?od='. $od) }}"><i class="fa fa-clipboard-list"></i><span>@lang('documentOrders')</span></a>
</li>
