<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="nav-item {{ Request::is('correspondence/validar-correspondence-external*') ? 'active' : '' }}">
    <a href="{{ url('correspondence/validar-correspondence-external') }}" class="nav-link">
        <i class="nav-icon fas fa-tags"></i>
        <span>Validar correspondencia externa</span>
    </a>
</li>
