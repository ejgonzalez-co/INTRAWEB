<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-arrow-left"></i>
        <span>@lang('back')</span>
    </a>
</li>

<li class="{{ Request::is('expedientes-electronicos/expedientes*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('expedientes.index') }}" style="width: 90%;">
        <i class="nav-icon fas fa-file-alt"></i>
        <span>Expedientes electrónicos</span>
    </a>
    <i onclick="toggleContent(this, '#showSubmemuExpedientes')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuExpedientes'" style="margin: 10px;"></i>
</li>

<div :id="'showSubmemuExpedientes'" class="{{ Request::is('expedientes-electronicos/validar-documento-expediente') ? 'collapse show' : 'collapse' }}">
    <ul class="nav menuNavDesplegable">
        <li class="{{ Request::is('expedientes-electronicos/validar-documento-expediente') ? 'active' : '' }}">
            <a href="{{ url('expedientes-electronicos/validar-documento-expediente') }}" class="nav-link">
                {{-- <i class="far fa-list-alt"></i> --}}
                <span>Validar documentos del expediente</span>
            </a>
        </li>
    </ul>
</div>
{{-- @if (Auth::user()->hasRole('Admin Expedientes Electrónicos') || Auth::user()->hasRole('Operador Expedientes Electrónicos'))
    <li class="nav-item {{ Request::is('expedientes-electronicos/tipos-documentals*') ? 'active' : '' }}">
        <a href="{{ route('tipos-documentals.index') }}" class="nav-link">
            <i class="fas fa-paste"></i>
            <p>Tipos de documentos</p>
        </a>
    </li>
@endif --}}
