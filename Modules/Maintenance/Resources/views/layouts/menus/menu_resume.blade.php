@if (session('outside') == false )

    <li class="{{ Request::is('home*') ? 'active' : '' }}">
        <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
    </li>

    {{-- <li class="{{ Request::is('maintenance/resume-machinery-vehicles-yellows*') ? 'active' : '' }}">
        <a href="{{ route('resume-machinery-vehicles-yellows.index') }}"><i class="fa fa-clipboard-list"></i><span>@lang('Resume-machinery-vehicles-yellows')</span></a>
    </li> --}}

@else

    <li class="{{ Request::is('/dashboard') ? 'active' : '' }}">
        <a href="{{ url('/logout-outside-vendor') }}"><i class="fa fa-power-off"></i><span>Salir</span></a>
    </li>

@endif

@if(Request::is('maintenance/resume-machinery-vehicles-yellows*') || Request::is('maintenance/asset-managements*'))
    <li class="{{ Request::is('maintenance/resume-machinery-vehicles-yellows*') ? 'active' : '' }}">
        <a href="{{ route('resume-machinery-vehicles-yellows.index') }}"><i class="fa fa-clipboard-list"></i><span>@lang('Resume-machinery-vehicles-yellows')</span></a>
    </li>
    <li class="{{ Request::is('maintenance/asset-managements*') ? 'active' : '' }}">
        <a href="{{ route('asset-managements.index') }}"><i class="fa fa-clipboard-list"></i><span>@lang('Mantenimientos')</span></a>
    </li>

@else
    @if(Auth::user()->hasRole("Administrador de mantenimientos"))
        <li class="{{ Request::is('maintenance/request-need*') ? 'active' : '' }}">
            <a href="{{ route('request-needs.index') }}"><i class="fa fa-edit"></i><span>@lang('requestNeeds')</span></a>
        </li>
        <li class="{{ Request::is('/maintenance/stock?t=CAM*') ? 'active' : '' }}">
            <a href="{{ url('/maintenance/stock?t=CAM') }}"><i class="fas fa-clipboard-list"></i><span>@lang('Stock - Inventario CAM')</span></a>
        </li>
        <li class="{{ Request::is('/maintenance/stock?t=Aseo*') ? 'active' : '' }}">
            <a href="{{ url('/maintenance/stock?t=Aseo') }}"><i class="fas fa-clipboard-list"></i><span>@lang('Stock - Inventario Aseo')</span></a>
        </li>
    @endif

@endif