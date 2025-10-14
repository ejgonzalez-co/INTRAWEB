<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<!-- Documentos -->
<li class="nav-item {{ Request::is('calidad/documentos*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('documentos-calidad.index') }}" class="nav-link" style="width: 90%;"><i class="nav-icon fas fa-file-alt"></i><span>Documentos</span></a>
    {{-- Valida si el usuario en sesión es un administrador de calidad --}}
    @if (Auth::user()->hasRole('Admin Documentos de Calidad'))
        <i onclick="toggleContent(this, '#showSubmemuDocumentos')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuDocumentos'" style="margin: 10px;"></i>
    @endif
</li>
{{-- Valida si el usuario en sesión es un administrador de calidad --}}
@if (Auth::user()->hasRole('Admin Documentos de Calidad'))
    <div :id="'showSubmemuDocumentos'" class="{{ Request::is('calidad/arbol-documentos*') ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            <li class="nav-item {{ Request::is('calidad/arbol-documentos*') ? 'active' : '' }}">
                <a href="{{ url('calidad/arbol-documentos') }}" class="nav-link">
                    <span>Árbol de documentos</span>
                </a>
            </li>
        </ul>
    </div>

    <li class="nav-item {{ Request::is('calidad/tipo-sistemas*') ? 'active' : '' }}">
        <a href="{{ route('tipo-sistemas.index') }}" class="nav-link">
            <i class="nav-icon fas fa-server"></i>
            <span>Tipos de sistemas</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('calidad/documento-tipo-documentos*') ? 'active' : '' }}">
        <a href="{{ route('documento-tipo-documentos.index') }}" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <span>Tipos de documentos</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('calidad/tipo-procesos*') ? 'active' : '' }}">
        <a href="{{ route('tipo-procesos.index') }}" class="nav-link">
            <i class="nav-icon fas fa-object-group"></i>
            <span>Tipos de procesos</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('calidad/procesos*') ? 'active' : '' }}">
        <a href="{{ route('procesos.index') }}" class="nav-link">
            <i class="nav-icon fas fa-object-ungroup"></i>
            <span>Procesos</span>
        </a>
    </li>
@endif

<li class="nav-item {{ Request::is('calidad/documento-solicitud-documentals*') ? 'active' : '' }}">
    <a href="{{ route('documento-solicitud-documentals.index') }}" class="nav-link">
        <i class="nav-icon fas fa-pen-square"></i>
        <span>Solicitudes de documentos</span>
    </a>
</li>

<!-- Documentos -->
<li class="nav-item {{ Request::is('calidad/mapa-procesos-publico') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('mapa-procesos-publico') }}" class="nav-link" style="width: 90%;"><i class="nav-icon fas fa-file-alt"></i><span>Vista de mapa de procesos</span></a>
    {{-- Valida si el usuario en sesión es un administrador de calidad --}}
    @if (Auth::user()->hasRole('Admin Documentos de Calidad'))
        <i onclick="toggleContent(this, '#showSubmemuMapaProcesos')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuMapaProcesos'" style="margin: 10px;"></i>
    @endif
</li>
{{-- Valida si el usuario en sesión es un administrador de calidad --}}
@if (Auth::user()->hasRole('Admin Documentos de Calidad'))
    <div :id="'showSubmemuMapaProcesos'" class="{{ Request::is('calidad/mapa-procesos') ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            <li class="nav-item {{ Request::is('calidad/mapa-procesos') ? 'active' : '' }}">
                <a href="{{ route('mapa-procesos.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-code-branch"></i>
                    <span>Mapa de procesos</span>
                </a>
            </li>
        </ul>
    </div>
@endif

@push('scripts')
<script>
    // Función para alternar entre expandido y colapsado los menús de la barra lateral
    function toggleContent(container, target) {
        // Alterna la clase expanded al element <i> del menú principal
        container.classList.toggle('expanded');

        // Cambia la dirección de la flecha
        if (container.classList.contains('expanded')) {
            container.classList.remove('fa-chevron-down');
            container.classList.add('fa-chevron-up');
            // Obtiene el contenedor a desplegar por medio del target recibido por parámetro
            var arrowIcon = document.querySelector(target);
            // Espera 2 segundos para añadir la clase show al contenedor submenú
            setTimeout(() => {
                arrowIcon.classList.add('show');
            }, 200);
        } else {
            container.classList.remove('fa-chevron-up');
            container.classList.add('fa-chevron-down');
            // Obtiene el contenedor a colapsar por medio del target recibido por parámetro
            var arrowIcon = document.querySelector(target);
            // Espera 2 segundos para remover la clase show al contenedor submenú
            setTimeout(() => {
                arrowIcon.classList.remove('show');
            }, 200);
        }
    }
</script>
@endpush
