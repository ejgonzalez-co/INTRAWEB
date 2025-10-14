<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="nav-item {{ Request::is('correspondence/validar-correspondence-internal*') ? 'active' : '' }}">
    <a href="{{ url('correspondence/validar-correspondence-internal') }}" class="nav-link">
        <i class="nav-icon fas fa-tags"></i>
        <span>Validar correspondencia interna</span>
    </a>
</li>
