<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<li class="{{ Request::is('documentos-electronicos/documentos*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('documentos.index') }}" style="width: 90%;"><i class="nav-icon fas fa-file-alt"></i><span>Documentos</span></a>
    <i onclick="toggleContent(this, '#showSubmemuDocumentos')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuDocumentos'" style="margin: 10px;"></i>
</li>

<div :id="'showSubmemuDocumentos'" class="{{ Request::is('documentos-electronicos/validar-documento-electronico') ? 'collapse show' : 'collapse' }}">
    <ul class="nav menuNavDesplegable">
        <li class="{{ Request::is('documentos-electronicos/validar-documento-electronico') ? 'active' : '' }}">
            <a href="{{ url('documentos-electronicos/validar-documento-electronico') }}" class="nav-link">
                {{-- <i class="far fa-list-alt"></i> --}}
                <span>Validar documento electrónico</span>
            </a>
        </li>
    </ul>
</div>

{{-- Valida si es un admin de documentos electrónicos para mostrarle el ítem de tipos de documentos --}}
@if(Auth::user()->hasRole("Admin Documentos Electrónicos"))
    <li class="nav-item {{ Request::is('documentos-electronicos/tipo-documentos*') ? 'active' : '' }}">
        <a href="{{ route('tipo-documentos.index') }}" class="nav-link">
            <i class="nav-icon fas fa-list-ul"></i>
            <span>Tipos de Documentos</span>
        </a>
    </li>
@endif

{{-- <li class="nav-item">
    <a href="{{ route('documentoAnotacions.index') }}" class="nav-link {{ Request::is('documentoAnotacions*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Documento Anotacions</p>
    </a>
</li> --}}
