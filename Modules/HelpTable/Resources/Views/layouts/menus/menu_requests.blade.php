@if(Auth::user()->hasRole(["Administrador TIC","Soporte TIC","Aprobación concepto técnico TIC","Revisor concepto técnico TIC"]))
    <li class="{{ Request::is('help-table/home*') ? 'active' : '' }}">
        <a href="{!! url('dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
    </li>

    <li class="{{ Request::is('help-table/tic-requests*') ? 'active' : '' }}" style="display: flex;">
        <a href="{{ url('help-table/tic-requests') }}" style="width: 90%;"><i class="fa fa-edit"></i><span>Solicitudes</span></a>
        <i onclick="toggleContent(this, '#showSubmemuSolicitudes')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuSolicitudes'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmemuSolicitudes'" class="{{ Request::is('help-table/tic-type-requests') || Request::is('help-table/technical-concepts*') || Request::is('help-table/tic-type-tic-categories*') ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            <li class="{{ Request::is('help-table/technical-concepts') ? 'active' : '' }}">
                <a href="{{ url('help-table/technical-concepts') }}" class="nav-link">
                    {{-- <i class="far fa-list-alt"></i> --}}
                    <span>Solicitudes conceptos técnicos</span>
                </a>
            </li>
            @role("Administrador TIC")
                <li class="nav-item {{ Request::is('help-table/tic-type-requests*') ? 'active' : '' }}">
                    <a href="{{ url('help-table/tic-type-requests') }}" class="nav-link">
                        {{-- <i class="nav-icon fas fa-tags"></i> --}}
                        <span>Tipos de solicitudes</span>
                    </a>
                </li>

                <li class="{{ Request::is('help-table/tic-type-tic-categories') ? 'active' : '' }}">
                    <a href="{{ url('help-table/tic-type-tic-categories') }}" class="nav-link">
                        {{-- <i class="nav-icon fas fa-tags"></i> --}}
                        <span>Categorías</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ Request::is('categories*') ? 'active' : '' }}">
                        <span>Categorias</span>
                    </a>
                </li> -->
            @endrole
        </ul>
    </div>

  

    <li class="{{ Request::is('help-table/equipment-resumes*') ? 'active' : '' }}" style="display: flex;">
        <a href="{{ url('help-table/equipment-resumes') }}" style="width: 90%;"><i class="fas fa-warehouse"></i><span>Inventario</span></a>
        <i onclick="toggleContent(this, '#showSubmemuInventario')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuInventario'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmemuInventario'" class="{{ Request::is('help-table/tic-type-assets') || Request::is('help-table/tic-maintenances*') ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            <li class="nav-item {{ Request::is('help-table/tic-type-assets*') ? 'active' : '' }}">
                <a href="{{ url('help-table/tic-type-assets') }}" class="nav-link">
                    {{-- <i class="nav-icon fas fa-tags"></i> --}}
                    <span>Tipos de activos</span>
                </a>
            </li>
            <li class="{{ Request::is('help-table/tic-maintenances') || Request::is('help-table/tic-maintenances-equipment*') ? 'active' : '' }}">
                <a href="{{ url('help-table/tic-maintenances') }}" class="nav-link">
                    {{-- <i class="far fa-list-alt"></i> --}}
                    <span>Mantenimientos</span>
                </a>
            </li>
        </ul>
    </div>

    <li class="{{ Request::is('help-table/tic-knowledge-bases*') ? 'active' : '' }}" style="display: flex;">
        <a href="{{ url('help-table/tic-knowledge-bases') }}" style="width: 90%;"><i class="fas fa-clipboard-list"></i><span>Base de conocimiento</span></a>
    </li>

    <li class="{{ Request::is('help-table/users*') ? 'active' : '' }}" style="display: flex;">
        <a href="{{ url('help-table/users') }}" style="width: 90%;"><i class="fas fa-users"></i><span>Gestión de técnicos y proveedores</span></a>
        <i onclick="toggleContent(this, '#showSubmemuProveedores')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuProveedores'" style="margin: 10px;"></i>
    </li>

    @role("Administrador TIC")
        <li class="nav-item {{ Request::is('help-table/data-analytics*') ? 'active' : '' }}">
            <a href="{{ url('help-table/data-analytics') }}" class="nav-link">
                <i class="far fa-chart-bar"></i>
                <span>Analítica de datos</span>
            </a>
        </li>
    @endrole

    <div :id="'showSubmemuProveedores'" class="{{ Request::is('help-table/tic-providers') ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            <li class="{{ Request::is('help-table/tic-providers') ? 'active' : '' }}">
                <a href="{{ url('help-table/tic-providers') }}" class="nav-link">
                    {{-- <i class="far fa-list-alt"></i> --}}
                    <span>Proveedores TIC</span>
                </a>
            </li>
        </ul>
    </div>
    @if(Auth::user()->hasRole(["Administrador TIC"]))
    <li class="{{ Request::is('sedes*') ? 'active' : '' }}">
        <a href="{{ route('sedes.index') }}"><i class="fa fa-edit"></i><span>@lang('Sedes')</span></a>
    </li>
    @endif



@else
    <li class="{{ Request::is('home*') ? 'active' : '' }}">
        <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
    </li>

        <li class="{{ Request::is('help-table/tic-requests*') ? 'active' : '' }}" style="display: flex;">
        <a href="{{ url('help-table/tic-requests') }}" style="width: 90%;"><i class="fa fa-edit"></i><span>Solicitudes</span></a>
        <i onclick="toggleContent(this, '#showSubmemuSolicitudes')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuSolicitudes'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmemuSolicitudes'" class="{{ Request::is('help-table/tic-type-requests') || Request::is('help-table/technical-concepts*') ? 'collapse show' : 'collapse' }}">
        <ul class="nav menuNavDesplegable">
            @role("Usuario TIC")
                <li class="{{ Request::is('help-table/technical-concepts') ? 'active' : '' }}">
                    <a href="{{ url('help-table/technical-concepts') }}" class="nav-link">
                        {{-- <i class="far fa-list-alt"></i> --}}
                        <span>Solicitudes conceptos técnicos</span>
                    </a>
                </li>
            @endrole
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
