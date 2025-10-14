<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<li class="nav-item {{ Request::is('pqrs/p-q-r-s*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('p-q-r-s.index') }}" class="nav-link" style="width: 90%;"><i class="nav-icon fas fa-bullhorn"></i><span>PQRS</span></a>
    {{-- Valida si el usuario en sesión es un administrador --}}
    @if (Auth::user()->hasRole('Administrador de requerimientos'))
        <i onclick="toggleContent(this, '#showSubmemuPQRS')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuPQRS'" style="margin: 10px;"></i>
    @endif
</li>
{{-- Valida si el usuario en sesión es un administrador --}}
@if (Auth::user()->hasRole('Administrador de requerimientos'))
    <div :id="'showSubmemuPQRS'" class="{{ Request::is('pqrs/p-q-r-eje-tematicos*') || Request::is('pqrs/p-q-r-tipo-solicituds*') ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            <li class="nav-item {{ Request::is('pqrs/p-q-r-eje-tematicos*') ? 'active' : '' }}">
                <a href="{{ route('p-q-r-eje-tematicos.index') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-paste"></i> --}}
                    <span>Ejes Temáticos</span>
                </a>
            </li>

            <li class="nav-item {{ Request::is('pqrs/p-q-r-tipo-solicituds*') ? 'active' : '' }}">
                <a href="{{ route('p-q-r-tipo-solicituds.index') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-sliders-h"></i> --}}
                    <span>Tipos de Solicitudes</span>
                </a>
            </li>
        </ul>
    </div>
@endif

{{-- Valida si el usuario en sesión es un administrador --}}
@if (Auth::user()->hasRole('Administrador de requerimientos'))
    <li class="nav-item {{ Request::is('pqrs/citizens-pqr*') ? 'active' : '' }}">
        <a href="{{ route('citizens-pqr.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <span>@lang('Citizens')</span>
        </a>
    </li>
@endif
{{-- Valida si tiene la integración con un sitio de Joomla habilitada, además, si tiene username es un usuario antiguo que requiere integración --}}
@if (Auth::user()->username)
    <li class="nav-item {{ Request::is('pqrs/repository-pqr*') ? 'active' : '' }}">
       
        @if (Auth::user()->hasRole('Administrador de requerimientos'))
            <a href="{{ config('app.url_joomla').'/'.'index.php?option=com_formasonline&formasonlineform=FormaInicioAdmin' }}" class="nav-link"  target="_blank">
                <i class="nav-icon fas fa-history"></i>
                <span>@lang('Histórico de PQRS')</span>
            </a>
        @else
            <a href="{{ config('app.url_joomla').'/'.'index.php?option=com_formasonline&formasonlineform=FormaInicioFunc' }}" class="nav-link"  target="_blank">
                <i class="nav-icon fas fa-history"></i>
                <span>@lang('Histórico de PQRS')</span>
            </a>
        @endif


    </li>
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
