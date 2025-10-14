<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('correspondence/internals*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('internals.index') }}" style="width: 90%;"><i class="fa fa-file-signature"></i><span>Correspondencia Interna</span></a>
    <i onclick="toggleContent(this, '#showSubmemuInterna')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuInterna'" style="margin: 10px;"></i>
</li>

<div :id="'showSubmemuInterna'" class="{{ Request::is('correspondence/internal-types') || Request::is('correspondence/validar-correspondence-internal*') ? 'collapse show' : 'collapse' }}">
    <ul class="nav menuNavDesplegable">
        @role('Correspondencia Interna Admin')
            <li class="{{ Request::is('correspondence/internal-types') ? 'active' : '' }}">
                <a href="{{ route('internal-types.index') }}" class="nav-link">
                    {{-- <i class="far fa-list-alt"></i> --}}
                    <span>@lang('Types Documentaries') Interna</span>
                </a>
            </li>
        @endrole

        {{-- Valida si tiene la integración con un sitio de Joomla habilitada, además, si tiene username es un usuario antiguo que requiere integración --}}
        @if (config("app.integracion_sitio_joomla") && Auth::user()->username)
            <li class="nav-item {{ Request::is('correspondence/repository-correspondence-internal*') ? 'active' : '' }}">
                <a href="{{ url('correspondence/repository-correspondence-internal') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-tags"></i> --}}
                    <span>Histórico de correspondencia interna</span>
                </a>
            </li>
        @endif
        <li class="nav-item {{ Request::is('correspondence/validar-correspondence-internal*') ? 'active' : '' }}">
            <a href="{{ url('correspondence/validar-correspondence-internal') }}" class="nav-link">
                {{-- <i class="nav-icon fas fa-tags"></i> --}}
                <span>Validar correspondencia interna</span>
            </a>
        </li>
    </ul>
</div>


<li class="{{ Request::is('correspondence/external-receiveds*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('external-receiveds.index') }}" style="width: 90%;"><i class="fa fa-mail-bulk"></i><span>Correspondencia Recibida</span></a>
    {{-- Valida si tiene la integración con un sitio de Joomla habilitada, además, si tiene username es un usuario antiguo que requiere integración. Valida si tiene los permisos adecuados --}}
    @if (Auth::user()->hasRole('Correspondencia Recibida Admin') ||  Auth::user()->username)
        <i onclick="toggleContent(this, '#showSubmemuRecibida')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuRecibida'" style="margin: 10px;"></i>
    @endif
</li>

<div :id="'showSubmemuRecibida'" class="{{ Request::is('correspondence/types-documentaries*') ? 'collapse show' : 'collapse' }}">
    <ul class="nav menuNavDesplegable">

        <li class="{{ Request::is('correspondence/types-documentaries*') ? 'active' : '' }}">
            <a href="{{ route('types-documentaries.index') }}" class="nav-link">
                {{-- <i class="far fa-list-alt"></i> --}}
                <span>@lang('Types Documentaries') Recibida</span>
            </a>
        </li>

        {{-- Valida si tiene la integración con un sitio de Joomla habilitada, además, si tiene username es un usuario antiguo que requiere integración --}}
        @if (config("app.integracion_sitio_joomla") && Auth::user()->username)
            <li class="nav-item {{ Request::is('correspondence/repository-external-receiveds*') ? 'active' : '' }}">
                <a href="{{ url('correspondence/repository-external-receiveds') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-tags"></i> --}}
                    <span>Histórico de correspondencia recibida</span>
                </a>
            </li>
        @endif
    </ul>
</div>

@role('Correspondencia Recibida Admin')
    {{-- @if (isset($mostrar) && $mostrar === 'si') --}}
    {{-- <li class="{{ Request::is('correspondence/correo-integrados*') ? 'active' : '' }}">
        <a href="{{ route('correo-integrados.index') }}" class="nav-link">
            <i class="fa fa-envelope"></i>
            <span>Comunicaciones por correo</span>
        </a>
    </li> --}}

    <li class="{{ Request::is('correspondence/correo-integrados') ? 'active' : '' }}" style="display: flex;">
        <a href="{{ route('correo-integrados.index') }}" style="width: 90%;"><i class="fa fa-envelope"></i><span>Comunicaciones por correo</span></a>
        <i onclick="toggleContent(this, '#showSubmemuCorreo')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuCorreo'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmemuCorreo'" class="{{ Request::is('correspondence/correo-integrados') || Request::is('correspondence/correo-integrados-list*') ||Request::is('correspondence/correo-integrado-spams*')   ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            <li class="nav-item {{ Request::is('correspondence/correo-integrado-spams*') ? 'active' : '' }}">
                <a href="{{ url('correspondence/correo-integrado-spams') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-tags"></i> --}}
                    <span>Correos en la Bandeja de Spam</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('correspondence/correo-integrados-list*') ? 'active' : '' }}">
                <a href="{{ url('correspondence/correo-integrados-list') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-tags"></i> --}}
                    <span>Administración de Correo Integrado</span>
                </a>
            </li>
        </ul>
    </div>
    {{-- @endif --}}
@endrole

<li class="{{ Request::is('correspondence/externals*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('externals.index') }}" style="width: 90%;"><i class="fa fa-file-import"></i><span>Correspondencia Enviada</span></a>
    <i onclick="toggleContent(this, '#showSubmemuEnviada')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuEnviada'" style="margin: 10px;"></i>
</li>

<div :id="'showSubmemuEnviada'" class="{{ Request::is('correspondence/external-types') || Request::is('correspondence/validar-correspondence-external*') ? 'collapse show' : 'collapse' }}">
    <ul class="nav menuNavDesplegable">
        @role('Correspondencia Enviada Admin')
            <li class="{{ Request::is('correspondence/external-types') ? 'active' : '' }}">
                <a href="{{ route('external-types.index') }}" class="nav-link">
                    {{-- <i class="far fa-list-alt" ></i> --}}
                    <span>@lang('Types Documentaries') Enviada</span>
                </a>
            </li>
        @endrole

        {{-- Valida si tiene la integración con un sitio de Joomla habilitada, además, si tiene username es un usuario antiguo que requiere integración --}}
        @if (config("app.integracion_sitio_joomla") && Auth::user()->username)
            <li class="nav-item {{ Request::is('correspondence/repository-externals*') ? 'active' : '' }}">
                <a href="{{ url('correspondence/repository-externals') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-tags"></i> --}}
                    <span>Histórico de correspondencia enviada</span>
                </a>
            </li>
        @endif
        <li class="nav-item {{ Request::is('correspondence/validar-correspondence-external*') ? 'active' : '' }}">
            <a href="{{ url('correspondence/validar-correspondence-external') }}" class="nav-link">
                {{-- <i class="nav-icon fas fa-tags"></i> --}}
                <span>Validar correspondencia externa</span>
            </a>
        </li>
    </ul>
</div>

{{-- @if (isset($mostrar) && $mostrar === 'si') --}}

@if (Auth::user()->hasRole('Correspondencia Recibida Admin') )
    <li class="nav-item {{ Request::is('correspondence/planillas*') ? 'active' : '' }}" style="display: flex;">
        <a href="{{ route('planillas.index') }}" class="nav-link" style="width: 90%;"><i class="nav-icon fas fa-th"></i><span>Planillas</span></a>
        <i onclick="toggleContent(this, '#showSubmemuPlanillas')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuPlanillas'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmemuPlanillas'" class="{{ Request::is('correspondence/planilla-rutas') ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            <li class="nav-item {{ Request::is('correspondence/planilla-rutas*') ? 'active' : '' }}">
                <a href="{{ route('planilla-rutas.index') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-tags"></i> --}}
                    <span>Rutas de planilla</span>
                </a>
            </li>
        </ul>
    </div>

    @endif

{{-- @endif --}}

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
