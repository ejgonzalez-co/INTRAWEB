@if (Auth::check())
    <li class="{{ Request::is('home*') ? 'active' : '' }}">
        <a href="{!! url('/maintenance/request-needs') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
    </li>

    <li class="{{ Request::is('maintenance/addition-spare-part-activities*') ? 'active' : '' }}">
        <a href="#"><i class="fas fa-edit"></i><span>@lang('Solicitudes de adiciones')</span></a>
    </li>
@else
    <li class="{{ Request::is('home*') ? 'active' : '' }}">
        <a href="{!! url('/maintenance/request-need-orders?rn=MsQs==') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
    </li>

    <li class="{{ Request::is('maintenance/addition-spare-part-activities*') ? 'active' : '' }}">
        <a href="#"><i class="fas fa-edit"></i><span>@lang('Solicitudes de adiciones')</span></a>
    </li>
@endif