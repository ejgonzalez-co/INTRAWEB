<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ route('equipment-resumes.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li style="display: flex;" class="{{ Request::is('help-table/config-towers*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-towers') }}" style="width: 90%;"><i class="fas fa-cog"></i><span> @lang('Configuración Torres')</span></a>
    <i onclick="toggleContent(this, '#showSubmemuTower')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuTower'" style="margin: 10px;"></i>
</li>
<div :id="'showSubmemuTower'"  class="{{ Request::is('help-table/config-towers*') || Request::is('help-table/config-tower-references') || Request::is('help-table/config-tower-sizes') || Request::is('help-table/config-tower-processors') || Request::is('help-table/config-tower-memory-rams') || Request::is('help-table/config-tower-ssd-capacities') ||  Request::is('help-table/config-tower-hdd-capacities') || Request::is('help-table/config-tower-video-cards') ||  Request::is('help-table/config-shared-folders') || Request::is('help-table/config-network-cards') ? 'collapse show' : 'collapse' }}">
    <ul class="nav menuNavDesplegable">
        
        <li class="{{ Request::is('help-table/config-tower-references') ? 'active' : '' }}">
            <a href="{{ route('config-tower-references.index') }}" >
                <i class="nav-icon fas fa-cog"></i>
                <p>Referencia</p>
            </a>
        </li>

        <li class="{{ Request::is('help-table/config-tower-sizes*') ? 'active' : '' }}">
            <a href="{{ route('config-tower-sizes.index') }}" >
                <i class="nav-icon fas fa-cog"></i>
                <p>Tamaño torre</p>
            </a>
        </li>


        <li class="{{ Request::is('help-table/config-tower-processors*') ? 'active' : '' }}">
            <a href="{{ route('config-tower-processors.index') }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Procesador</p>
            </a>
        </li>

        <li class="{{ Request::is('help-table/config-tower-memory-rams*') ? 'active' : '' }}">
            <a href="{{ route('config-tower-memory-rams.index') }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Memoria RAM</p>
            </a>
        </li>

        <li class="{{ Request::is('help-table/config-tower-ssd-capacities*') ? 'active' : '' }}">
            <a href="{{ route('config-tower-ssd-capacities.index') }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Capacidad SSD</p>
            </a>
        </li>

        <li class="{{ Request::is('help-table/config-tower-hdd-capacities*') ? 'active' : '' }}">
            <a href="{{ route('config-tower-hdd-capacities.index') }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Capacidad HDD</p>
            </a>
        </li>

        <li class="{{ Request::is('help-table/config-tower-video-cards*') ? 'active' : '' }}">
            <a href="{{ route('config-tower-video-cards.index') }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Tarjeta de video/gráfica</p>
            </a>
        </li>

        <li class="{{ Request::is('help-table/config-shared-folders*') ? 'active' : '' }}">
            <a href="{{ route('config-shared-folders.index') }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Carpeta Compartida</p>
            </a>
        </li>

        <li class="{{ Request::is('help-table/config-network-cards*') ? 'active' : '' }}">
            <a href="{{ route('config-network-cards.index') }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Marca Tarjeta de red</p>
            </a>
        </li>

    </ul>
</div>
{{-- <li class="{{ Request::is('help-table/config-keyboards*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-keyboards') }}"><i class="fas fa-cog"></i><span> @lang('Configuración Teclados')</span></a>
</li> --}}
{{-- <li class="{{ Request::is('help-table/config-mouses*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-mouses') }}"><i class="fas fa-cog"></i><span> @lang('Configuración Mouses')</span></a>
</li> --}}
<li class="{{ Request::is('help-table/config-monitors*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-monitors') }}"><i class="fas fa-cog"></i><span> @lang('Configuración Monitores')</span></a>
</li>
<li class="{{ Request::is('help-table/config-operation-systems*') ? 'active' : '' }}">
    <a href="{{ url('help-table/config-operation-systems') }}"><i class="fas fa-cog"></i><span> @lang('Configuración Sistemas Operativos')</span></a>
</li>

{{-- class="{{ Request::is('help-table/config-office-versions*') || Request::is('help-table/config-storage-statuses*') || Request::is('help-table/config-unnecessary-apps*')? 'active' : '' }}" --}}
<li :data-target="'#showSubmemuSoftware'" data-toggle="collapse"  style="display: flex;" >
    <a href="#"  style="width: 90%;" onclick="toggleContent(this, '#showSubmemuSoftware')"><i class="fas fa-cog"></i><span> @lang('Configuración Software')</span></a>
    <i class="fa fa-chevron-down" aria-hidden="true" class="fa fa-chevron-down"  style="margin: 10px;"></i>
</li>
<div :id="'showSubmemuSoftware'"  class="{{ Request::is('help-table/config-towers*') || Request::is('help-table/config-tower-references') || Request::is('help-table/config-tower-sizes') || Request::is('help-table/config-tower-processors') || Request::is('help-table/config-tower-memory-rams') || Request::is('help-table/config-tower-ssd-capacities') ||  Request::is('help-table/config-tower-hdd-capacities') || Request::is('help-table/config-tower-video-cards') ||  Request::is('help-table/config-shared-folders') || Request::is('help-table/config-network-cards') ? 'collapse show' : 'collapse' }}">
    <ul class="nav menuNavDesplegable">
        
        <li class="{{ Request::is('help-table/config-office-versions') ? 'active' : '' }}">
            <a href="{{ route('config-office-versions.index')  }}" >
                <i class="nav-icon fas fa-cog"></i>
                <p>Versión Office</p>
            </a>
        </li>

        <li class="{{ Request::is('help-table/config-storage-statuses*') ? 'active' : '' }}">
            <a href="{{ route('config-storage-statuses.index') }}" >
                <i class="nav-icon fas fa-cog"></i>
                <p>Estado Almacenamiento</p>
            </a>
        </li>


        <li class="{{ Request::is('help-table/config-unnecessary-apps*') ? 'active' : '' }}">
            <a href="{{ route('config-unnecessary-apps.index') }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Borrar aplicaciones no necesarios</p>
            </a>
        </li>
    </ul>
</div>