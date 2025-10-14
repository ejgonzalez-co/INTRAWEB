<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>Salir</span>
    </a>
</li>

<li class="nav-item {{ Request::is('documentos-electronicos/firmar*') || Request::is('documentos-electronicos/validar-codigo*') ? 'active' : '' }}">
    <a href="{{ route('documentos.index') }}" class="nav-link">
        <i class="nav-icon fas fa-file-alt"></i>
        <span>Procesar Documento</span>
    </a>
</li>
