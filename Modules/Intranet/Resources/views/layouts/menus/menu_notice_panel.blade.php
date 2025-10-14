<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


{{-- Noticias --}}
<li class="{{ Request::is('intranet/notices-public') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('noticesPublic') }}" style="display: flex; width: -webkit-fill-available;"><i class="far fa-newspaper"></i><span>Noticias</span></a>
    @role('Administrador intranet')
        <i onclick="toggleContent(this, '#noticias')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#noticias'" style="margin: 10px;"></i>
    @endrole
</li>

<div :id="'noticias'" class="collapse">
    <ul class="nav menuNavDesplegable">
        <li class="{{ Request::is('intranet/notices') ? '' : '' }}" >
            <a href="{{ url ('/intranet/notices') }}"><span>Administrar noticias</span></a>
        </li>
        <li class="{{ Request::is('intranet/categories') ? '' : '' }}" >
            <a href="{{ url ('/intranet/categories') }}"><span>Administrar categorias</span></a>
        </li>
    </ul>
</div>


{{-- Calendario de eventos --}}
<li class="{{ Request::is('intranet/events-public') ? '' : '' }}" style="display: flex;" >
    <a href="{{ route('eventsPublic') }}" style="display: flex; width: -webkit-fill-available;"><i class="fas fa-calendar-day"></i><span>Eventos</span></a>
    @role('Administrador intranet de eventos')
        <i onclick="toggleContent(this, '#evento')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#evento'" style="margin: 10px;"></i>
    @endrole
</li>

<div :id="'evento'" class="collapse">
    <ul class="nav menuNavDesplegable">
        <li class="{{ Request::is('intranet/notices') ? '' : '' }}" >
            <a href="{{ url ('/intranet/events') }}"><span>Administrar eventos</span></a>
        </li>
    </ul>
</div>


{{-- Descargas --}}
<li class="{{ Request::is('intranet/downloads-public') ? '' : '' }}" style="display: flex;">
    <a href="{{ route('downloadsPublic') }}" style="display: flex; width: -webkit-fill-available;"><i class="fas fa-file-download"></i><span>Descargas</span></a>
    @role('Administrador intranet de descargas')
        <i onclick="toggleContent(this, '#showDescargas')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showDescargas'" style="margin: 10px;"></i>
    @endrole

</li>

<div :id="'showDescargas'" class="collapse">
    <ul class="nav menuNavDesplegable">
        <li>
            <a href="{{ url('intranet/download-categories') }}" class="nav-link"><span class="mr-2">Administrar descargas</span></a>
        </li>
    </ul>
</div>


{{-- Encuestas de la entidad --}}
<li class="{{ Request::is('intranet/polls-public') ? '' : '' }}" style="display: flex;">
    <a href="{{ route('pollsPublic') }}" style="display: flex; width: -webkit-fill-available;"><i class="fab fa-wpforms"></i><span>Encuestas de la entidad</span></a>
    @role('Administrador intranet de encuestas')
        <i onclick="toggleContent(this, '#showEncuesta')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showEncuesta'" style="margin: 10px;"></i>
    @endrole
</li>

<div :id="'showEncuesta'" class="collapse">
    <ul class="nav menuNavDesplegable">
        <li>
            <a href="{{ url('intranet/polls') }}" class="nav-link"><span class="mr-2">Administrar encuestas</span></a>
        </li>
    </ul>
</div>


@push('scripts')
<script>
    // Obtiene el evento de la flecha de los items de menú del lado izquierdo
    // $("li").click(function () {
    //     // Itera entre el icon de la flecha hacia abajo y hacia arriba según sea el caso
    //     $(this).children('.fa').toggleClass('fa-chevron-down fa-chevron-up');
    // });

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
