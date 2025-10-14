<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/intranet/notices') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('intranet/downloads*') && !Request::is('intranet/downloads-tags*') ? 'active' : '' }}">
    <a href="{{ route('downloads.index') }}"><i class="fas fa-file-download"></i><span>@lang('Archivos')</span></a>
</li>

@role('Administrador intranet de descargas')
    <li class="{{ Request::is('intranet/download-categories*') ? 'active' : '' }}">
        <a href="{{ route('download-categories.index') }}"><i class="fa fa-folder"></i><span>@lang('Categorías')</span></a>
    </li>

    <li class="{{ Request::is('intranet/licenses*') ? 'active' : '' }}">
        <a href="{{ route('licenses.index') }}"><i class="fas fa-file-alt"></i><span>@lang('Licencias')</span></a>
    </li>

    <li class="{{ Request::is('intranet/downloads-tags*') ? 'active' : '' }}">
        <a href="{{ route('downloads-tags.index') }}"><i class="fa fa-tag"></i><span>@lang('Etiqueta')</span></a>
    </li>

    <li class="{{ Request::is('intranet/download-file-votes*') ? 'active' : '' }}">
        <a href="{{ route('download-file-votes.index') }}"><i class="fa fa-star"></i><span>@lang('Calificaciones')</span></a>
    </li>
@endrole