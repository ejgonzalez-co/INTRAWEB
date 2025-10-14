<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

@if(Auth::user()->hasRole("Gestión Documental Admin"))
    <li class="{{ Request::is('documentary-classification/type-documentaries*') ? 'active' : '' }}">
        <a href="{{ route('type-documentaries.index') }}"><i class="fa fa-inbox"></i><span>@lang('typeDocumentaries')</span></a>
    </li>

    <li class="{{ Request::is('documentary-classification/series-subseries*') ? 'active' : '' }}">
        <a href="{{ route('series-subseries.index') }}"><i class="fa fa-folder-open"></i><span>@lang('seriesSubSeries')</span></a>
    </li>

    <li class="{{ Request::is('documentary-classification/dependencias*') ? 'active' : '' }}">
        <a href="{{ route('dependencias.index') }}"><i class="fa fa-file"></i><span>@lang('dependencias')</span></a>
    </li>
@endif

<li class="{{ Request::is('documentary-classification/criterios-busquedas*') ? 'active' : '' }}">
    <a href="{{ route('criterios-busquedas.index') }}"><i class="fas fa-search"></i><p>Criterios de búsqueda</p></a>
</li>

<li class="{{ Request::is('documentary-classification/inventory-documentals*') ? 'active' : '' }}">
    <a href="{{ route('inventory-documentals.index') }}"><i class="fa fa-boxes"></i><span>@lang('inventoryDocumentals')</span></a>
</li>

<li class="{{ Request::is('documentary-classification/documentos-serie-subseries*') ? 'active' : '' }}">
    <a href="{{ url('documentary-classification/documentos-serie-subseries') }}"><i class="fas fa-archive"></i><span>@lang('Inventario documental digital')</span></a>
</li>




