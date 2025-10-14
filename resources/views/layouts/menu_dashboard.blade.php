{{-- Componente para cargar los valores del tablero inicial --}}
<dashboard :data-form="dataForm" :data-show="dataShow" :listado-noticias="dataForm.noticias" ref="dashboard"></dashboard>

@php
    $userRoles = auth()->user()->roles->pluck('name')->toArray();
    // Usuario actual en sesión
    $usuario = Auth::user();
@endphp

{{-- Correspondencia Recibida --}}
@if (!$usuario->hasRole('Ciudadano'))
    <li>
        <a href="{{ route('external-receiveds.index') }}" style="display: flex;">
            <i class="fa fa-mail-bulk"></i>
            <span>Correspondencia Recibida</span>
            <div v-if="dataForm.total_corr_recibida >= 0" v-text="'('+dataForm.total_corr_recibida+')'" style="float: right;"></div>
            <div class="loading" v-else>
                <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
            </div>
        </a>
    </li>
@endif

{{-- Correspondencia Enviada --}}
@if (!$usuario->hasRole('Ciudadano'))
    <li style="display: flex;">
        <a href="{{ route('externals.index') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-file-import"></i>
            <span>Correspondencia Enviada</span>
        </a>
        <div style="margin: 10px; margin-left: auto;">
            <div :class="dataForm.corr_enviada_firmar || dataForm.corr_enviada_aprobar || dataForm.corr_enviada_elaboracion || dataForm.corr_enviada_revision || dataForm.corr_enviada_devuelto ? 'registrosPendientes' : ''"></div>
        </div>
        <i onclick="toggleContent(this, '#showSubmemuEnviada')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuEnviada'" style="margin: 10px;"></i>
    </li>
@endif

<div :id="'showSubmemuEnviada'" class="collapse">
    <ul class="nav menuNavDesplegable">
        <li>
            <a href="{{ url('correspondence/externals?qsb='.base64_encode('Pendiente de firma')) }}" class="nav-link">
                <div>
                    {{-- <button class="mr-2 button__status-pending" style="color: #ff9800">Firmar</button> --}}
                    <button class="px-4 button__status-pending-correspondence">Firmar &nbsp;</button>
                    <span v-if="dataForm.corr_enviada_firmar >= 0" v-text="'('+dataForm.corr_enviada_firmar+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('correspondence/externals?qsb='.base64_encode('Aprobación')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-approved-correspondence">
                        Aprobar
                    </button>
                    <span v-if="dataForm.corr_enviada_aprobar >= 0" v-text="'('+dataForm.corr_enviada_aprobar+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('correspondence/externals?qsb='.base64_encode('Elaboración')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-assigned-correspondence">Elaborar</button>
                    <span v-if="dataForm.corr_enviada_elaboracion >= 0" v-text="'('+dataForm.corr_enviada_elaboracion+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('correspondence/externals?qsb='.base64_encode('Revisión')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-in_review-correspondence">Revisión</button>
                    <span v-if="dataForm.corr_enviada_revision >= 0" v-text="'('+dataForm.corr_enviada_revision+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('correspondence/externals?qsb='.base64_encode('Devuelto para modificaciones')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-cancelled-correspondence">Devuelto</button>
                    <span v-if="dataForm.corr_enviada_devuelto >= 0" v-text="'('+dataForm.corr_enviada_devuelto+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>

{{-- Correspondencia Interna --}}
@if (!$usuario->hasRole('Ciudadano'))
    <li style="display: flex;">
        <a href="{{ route('internals.index') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-file-signature"></i>
            <span>Correspondencia Interna</span>
        </a>
        <div style="margin: 10px; margin-left: auto;">
            <div :class="dataForm.corr_interna_firmar || dataForm.corr_interna_aprobar || dataForm.corr_interna_elaboracion || dataForm.corr_interna_revision || dataForm.corr_interna_devuelto ? 'registrosPendientes' : ''"></div>
        </div>
        <i onclick="toggleContent(this, '#showSubmemuInterna')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuInterna'" style="margin: 10px;"></i>
    </li>
@endif

<div :id="'showSubmemuInterna'" class="collapse ">
    <ul class="nav menuNavDesplegable">
        <li>
            <a href="{{ url('correspondence/internals?qsb='.base64_encode('Pendiente de firma')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-pending-correspondence">Firmar &nbsp;</button>
                    <span v-if="dataForm.corr_interna_firmar >= 0" v-text="'('+dataForm.corr_interna_firmar+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('correspondence/internals?qsb='.base64_encode('Aprobación')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-approved-correspondence">
                        Aprobar
                    </button>
                    <span v-if="dataForm.corr_interna_aprobar >= 0" v-text="'('+dataForm.corr_interna_aprobar+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('correspondence/internals?qsb='.base64_encode('Elaboración')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-assigned-correspondence">Elaborar</button>
                    <span v-if="dataForm.corr_interna_elaboracion >= 0" v-text="'('+dataForm.corr_interna_elaboracion+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('correspondence/internals?qsb='.base64_encode('Revisión')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-in_review-correspondence">Revisión</button>
                    <span v-if="dataForm.corr_interna_revision >= 0" v-text="'('+dataForm.corr_interna_revision+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('correspondence/internals?qsb='.base64_encode('Devuelto para modificaciones')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-cancelled-correspondence">Devuelto</button>
                    <span v-if="dataForm.corr_interna_devuelto >= 0" v-text="'('+dataForm.corr_interna_devuelto+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>

{{-- PQRS --}}
<li style="display: flex;">
    <a href="{{ route('p-q-r-s.index') }}" class="nav-link" style="display: flex; width: -webkit-fill-available;">
        <i class="fas fa-bullhorn"></i>
        <span>PQRS</span>
    </a>
    <div style="margin: 10px; margin-left: auto;">
        <div :class="dataForm.pqrs_vencidos || dataForm.pqrs_proximo_vencer || dataForm.pqrs_a_tiempo ? 'registrosPendientes' : ''"></div>
    </div>
    <i onclick="toggleContent(this, '#showSubmemuPQRS')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuPQRS'" style="margin: 10px;"></i>
</li>

<div :id="'showSubmemuPQRS'" class="collapse">
    <ul class="nav menuNavDesplegable">
        <li>
            <a href="{{ url('pqrs/p-q-r-s?qsb='.base64_encode('Vencido')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-cancelled-correspondence">Vencidos</button>
                    <span v-if="dataForm.pqrs_vencidos >= 0" v-text="'('+dataForm.pqrs_vencidos+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('pqrs/p-q-r-s?qsb='.base64_encode('Próximo a vencer')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-pending-correspondence">A vencer</button>
                    <span v-if="dataForm.pqrs_proximo_vencer >= 0" v-text="'('+dataForm.pqrs_proximo_vencer+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url('pqrs/p-q-r-s?qsb='.base64_encode('A tiempo')) }}" class="nav-link">
                <div>
                    <button class="px-4 button__status-in_review-correspondence">A tiempo</button>
                    <span v-if="dataForm.pqrs_a_tiempo >= 0" v-text="'('+dataForm.pqrs_a_tiempo+')'" style="float: right;"></span>
                    <div class="loading" v-else>
                        <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>

{{-- Valida los roles para mostrar el módulo de Gestión documental --}}
@if(config('app.mod_gestion_documental') && (in_array('Gestión Documental Admin', $userRoles) || in_array('Gestión Documental Consulta', $userRoles)))
    {{-- Gestión documental --}}
    <li style="display: flex;">
        <a href="{{ route('inventory-documentals.index') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-folder-open"></i>
            <span>Gestión documental</span>
        </a>
    </li>
@endif

{{-- Tipos de documentos electrónicos --}}
{{-- Valida los roles para mostrar el módulo--}}
@if(config('app.mod_documentos'))
<li>
    <a href="{{ route('documentos.index') }}" :style="dataForm.total_documentos_electronicos >= 0 ? 'display: flex;' : ''">
        <i class="fa fa-file-alt"></i>
        <span>Documentos Electrónicos</span>
        <div v-if="dataForm.total_documentos_electronicos >= 0" v-text="'('+dataForm.total_documentos_electronicos+')'" style="float: right;"></div>
        <div class="loading" v-else>
            <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
        </div>
    </a>
</li>
@endif

{{-- Valida los roles para mostrar el módulo de Configuración --}}
@if(config('app.mod_expedientes'))
    {{-- Configuración --}}
    <li style="display: flex;">
        <a href="{{ route('expedientes.index') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fas fa-file-contract"></i>
            <span>Expedientes Electrónicos</span>
        </a>
    </li>
@endif

{{-- Valida los roles para mostrar el módulo de Mesa de ayuda --}}
@if(config('app.mod_mesa_ayuda') && (in_array('Administrador TIC', $userRoles) || in_array('Soporte TIC', $userRoles) || in_array('Usuario TIC', $userRoles) || in_array('Proveedor TIC', $userRoles) || in_array('Aprobación concepto técnico TIC', $userRoles) || in_array('Revisor concepto técnico TIC', $userRoles)))
    <!-- Mesa de ayuda -->
    <li style="display: flex;">
        <a href="{{ url('help-table/tic-requests') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-comments"></i>
            <span>Mesa de ayuda</span>
        </a>

        <i onclick="toggleContent(this, '#showSubmemuMesaAyuda')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuMesaAyuda'" style="margin: 10px;"></i>
    </li>


    <div :id="'showSubmemuMesaAyuda'" class="collapse">
        <ul class="nav menuNavDesplegable">
            <li>
                <a href="{{ url('help-table/users') }}" class="nav-link">
                    <div>
                        <span class="mr-2">Gestión de técnicos y proveedores</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ url('help-table/equipment-resumes') }}" class="nav-link">
                    <div>
                        <span class="mr-2">Inventario</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ url('help-table/tic-requests') }}" class="nav-link">
                    <div>
                        <span class="mr-2">Solicitudes</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ url('help-table/tic-knowledge-bases') }}" class="nav-link">
                    <div>
                        <span class="mr-2">Base de conocimiento</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ url('help-table/equipment-resumes') }}" class="nav-link">
                    <div>
                        <span class="mr-2">Hoja de vida de los equipos</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
@endif


{{-- Gestión de recursos --}}
@if (Auth::user()->hasRole(['Administrador requerimiento gestión recursos','Operador Infraestuctura','Operador Suministros de consumo','Operador Suministros devolutivo / Asignación','Funcionario requerimiento gestión recursos']) )
    <li>
        <a href="{{ url('supplies-adequacies/requests') }}" style="display: flex;">
            <i class="fas fa-project-diagram"></i>
            <span>Requerimientos de Suministros y Adecuaciones</span>
        </a>
    </li>
@endif

{{-- Valida los roles para mostrar el módulo de Historias laborales --}}
@if(config('app.mod_historias_laborales') && (in_array('Administrador historias laborales', $userRoles) || in_array('Gestor hojas de vida', $userRoles)))
    {{-- Historias laborales --}}
    <li style="display: flex;">
        <a href="{{ url('work-histories/work-request') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-id-card"></i>
            <span>Historias Laborales</span>
        </a>
        <i onclick="toggleContent(this, '#showSubmemuHistoriasLaborales')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuHistoriasLaborales'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmemuHistoriasLaborales'" class="collapse">
        <ul class="nav menuNavDesplegable">
            @if(in_array('Administrador historias laborales', $userRoles))
                <li>
                    <a href="{{ url('work-histories/work-histories-actives') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Funcionarios activos y retirados</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Gestor hojas de vida', $userRoles))
                <li>
                    <a href="{{ url('work-histories/work-histories-actives') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Consulta funcionarios activos y retirados</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador historias laborales', $userRoles))
                <li>
                    <a href="{{ url('work-histories/work-hist-pensioners') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Pensionados, sustitutos y cuotas partes</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Gestor hojas de vida', $userRoles))
                <li>
                    <a href="{{ url('work-histories/work-hist-pensioners') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Consulta funcionarios pensionados, sustitutos y cuotas partes</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador historias laborales', $userRoles))
                <li>
                    <a href="{{ url('work-histories/work-request') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Autorizaciones</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Gestor hojas de vida', $userRoles))
                <li>
                    <a href="{{ url('work-histories/work-request') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Solicitudes de autorización</span>
                        </div>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif

{{---------------- Accesos trámites de VUV2 ----------------}}

{{-- Valida los roles para mostrar el módulo de Mantenimientos de activos V2 --}}
@if(in_array('Administrador de mantenimientos', $userRoles) ||
    in_array('Operario combustible', $userRoles) ||
    in_array('Admin Contratos', $userRoles) ||
    in_array('mant Operador apoyo administrativo', $userRoles) ||
    in_array('mant Operador Combustible EDS', $userRoles) ||
    in_array('mant Operador Combustible equipos menores', $userRoles) ||
    in_array('mant Operador Llantas', $userRoles) ||
    in_array('mant Operador Aceites', $userRoles) ||
    in_array('mant Operador líder', $userRoles) ||
    in_array('mant Consulta general', $userRoles) ||
    in_array('mant Consulta proceso', $userRoles) ||
    in_array('mant Líder de proceso', $userRoles) ||
    in_array('mant Almacén CAM', $userRoles) ||
    in_array('mant Almacén Aseo', $userRoles) ||
    in_array('mant Proveedor interno', $userRoles))
    <!-- Mantenimientos de activos V2 -->
    <li style="display: flex;">
        <a href="#" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-wrench"></i>
            <span>Mantenimientos de activos V2</span>
        </a>
        <i onclick="toggleContent(this, '#showSubmemuMantenimientos')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuMantenimientos'" style="margin: 10px;"></i>
    </li>


    <div :id="'showSubmemuMantenimientos'" class="collapse">
        <ul class="nav menuNavDesplegable">
            @if(in_array('Administrador de mantenimientos', $userRoles) || in_array('mant Consulta general', $userRoles))
                <li>
                    <a href="{{ url('maintenance/asset-types') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Configuración</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) || in_array('mant Consulta general', $userRoles) || in_array('mant Consulta proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/providers') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Gestión de proveedores</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) || in_array('mant Operador líder', $userRoles) || in_array('mant Consulta general', $userRoles) || in_array('mant Consulta proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/resume-machinery-vehicles-yellows') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Gestión de activos</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) ||
                in_array('Operario combustible', $userRoles) ||
                in_array('mant Operador apoyo administrativo', $userRoles) ||
                in_array('mant Operador Combustible EDS', $userRoles) ||
                in_array('mant Consulta general', $userRoles) ||
                in_array('mant Consulta proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/vehicle-fuels') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Gestión de combustibles</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('mant Operador Combustible equipos menores', $userRoles))
                <li>
                    <a href="{{ url('maintenance/minor-equipment-fuel') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Gestión de combustibles</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) ||
                in_array('Admin Contratos', $userRoles) ||
                in_array('mant Operador líder', $userRoles) ||
                in_array('mant Consulta general', $userRoles) ||
                in_array('mant Consulta proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/budget-provider') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Presupuesto</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) ||
                in_array('Admin Contratos', $userRoles) ||
                in_array('mant Operador líder', $userRoles) ||
                in_array('mant Consulta general', $userRoles) ||
                in_array('mant Consulta proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/indicators-index') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Indicadores</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) ||
                in_array('mant Operador apoyo administrativo', $userRoles) ||
                in_array('mant Operador Llantas', $userRoles) ||
                in_array('mant Consulta general', $userRoles) ||
                in_array('mant Consulta proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/tire-informations') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Gestión de llantas</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) ||
                in_array('mant Operador apoyo administrativo', $userRoles) ||
                in_array('mant Operador Aceites', $userRoles) ||
                in_array('mant Consulta general', $userRoles) ||
                in_array('mant Consulta proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/oil') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Gestión de aceites</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) || in_array('mant Líder de proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/request-needs') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Identificación de necesidades</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('mant Almacén Aseo', $userRoles) || in_array('mant Almacén CAM', $userRoles))
                <li>
                    <a href="{{ url('maintenance/request-need-orders?rn=MsQs==') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Identificación de necesidades</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador de mantenimientos', $userRoles) ||
                in_array('mant Operador apoyo administrativo', $userRoles) ||
                in_array('mant Operador Aceites', $userRoles) ||
                in_array('mant Consulta general', $userRoles) ||
                in_array('mant Consulta proceso', $userRoles))
                <li>
                    <a href="{{ url('maintenance/data-analytics') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Analitica de datos</span>
                        </div>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif

{{-- Valida los roles para mostrar el módulo de Admin inventario de medicamentos --}}
@if(in_array('Administrador de encuestas', $userRoles))
    <!-- Admin acuerdos de pago -->
    <li style="display: flex;">
        <a href="{{ url('citizen-poll/polls') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-file-alt"></i>
            <span>Encuestas de satisfacción</span>
        </a>
    </li>
@endif

{{-- Valida los roles para mostrar el módulo de LECAV2 --}}
@if(in_array('Administrador Leca', $userRoles) ||
    in_array('Analista fisicoquímico', $userRoles) ||
    in_array('Analista microbiológico', $userRoles) ||
    in_array('Personal de Apoyo', $userRoles) ||
    in_array('Recepcionista', $userRoles) ||
    in_array('Toma de Muestra', $userRoles) ||
    in_array('Operario Externo', $userRoles))
    <!-- Admin acuerdos de pago -->
    <li style="display: flex;">
        <a href="#" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-flask"></i>
            <span>LECA (módulo II)</span>
        </a>
        <i onclick="toggleContent(this, '#showSubmemuLECAV2')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuLECAV2'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmemuLECAV2'" class="collapse">
        <ul class="nav menuNavDesplegable">

            @if(in_array('Administrador Leca', $userRoles))
                <li>
                    <a href="{{ url('leca/customers') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Clientes</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador Leca', $userRoles))
                <li>
                    <a href="{{ url('leca/users-leca') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Recurso Humano</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador Leca', $userRoles))
                <li>
                    <a href="{{ url('leca/monthly-routines') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Rutinas de Trabajo</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador Leca', $userRoles))
                <li>
                    <a href="{{ url('leca/list-trials') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Gestión de ensayos</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador Leca', $userRoles) || in_array('Personal de Apoyo', $userRoles) || in_array('Toma de Muestra', $userRoles))
                <li>
                    <a href="{{ url('leca/start-samplings') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Toma de muestra</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador Leca', $userRoles) || in_array('Personal de Apoyo', $userRoles) || in_array('Recepcionista', $userRoles))
                <li>
                    <a href="{{ url('leca/sample-receptions') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Recepción de muestras</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador Leca', $userRoles) || in_array('Analista fisicoquímico', $userRoles) || in_array('Analista microbiológico', $userRoles) || in_array('Personal de Apoyo', $userRoles))
                <li>
                    <a href="{{ url('leca/list-ensayos-rutina') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Rutina de ensayos</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('Administrador Leca', $userRoles))
                <li>
                    <a href="{{ url('leca/report-managements') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Gestión de informes</span>
                        </div>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif

{{---------------- Fin trámites de VUV2 ----------------}}


{{---------------- Accesos enlaces destacados ----------------}}

<!-- Item enlaces destacados -->
<li style="display: flex;">
    <a href="#" style="display: flex; width: -webkit-fill-available;">
        <i class="fa fa-link"></i>
        <span>Enlaces destacados</span>
    </a>
    <i onclick="toggleContent(this, '#showSubmemuEnlaces')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuEnlaces'" style="margin: 10px;"></i>
</li>

<div :id="'showSubmemuEnlaces'" class="collapse">
    <ul class="nav menuNavDesplegable">
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_wrapper&view=wrapper&Itemid=391' }}" class="nav-link">
                <div>
                    <span class="mr-2">DIRECTORIO INTERNO</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=article&id=1208&Itemid=334' }}" class="nav-link">
                <div>
                    <span class="mr-2">ACTUALIZATE CON TIC</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=article&id=13&Itemid=287' }}" class="nav-link">
                <div>
                    <span class="mr-2">SISTEMA DE CONTROL INTERNO</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=article&id=28&Itemid=292' }}" class="nav-link">
                <div>
                    <span class="mr-2">SISTEMAS DE INFORMACION GEOGRAFICO (SIG)</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank"  href="{{ config('app.url_joomla').'/'.'index.php?option=com_formasonline&formasonlineform=Documentos' }}" class="nav-link">
                <div>
                    <span class="mr-2">SISTEMA DE GESTIÓN INTEGRADO (SGI)</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=category&layout=blog&id=26&Itemid=308' }}" class="nav-link">
                <div>
                    <span class="mr-2">SISTEMA UNICO DE INFORMACION (SUI)</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=article&id=426&Itemid=311' }}" class="nav-link">
                <div>
                    <span class="mr-2">COMITÉS EPA</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=article&id=205&Itemid=304' }}" class="nav-link">
                <div>
                    <span class="mr-2">MANUAL DE LA INTRANET</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=article&id=31&Itemid=295' }}" class="nav-link">
                <div>
                    <span class="mr-2">SINTRAEPA</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=article&id=2289&Itemid=396' }}" class="nav-link">
                <div>
                    <span class="mr-2">UTRAEPA</span>
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ config('app.url_joomla').'/'.'index.php?option=com_content&view=article&id=1742&Itemid=390' }}" class="nav-link">
                <div>
                    <span class="mr-2">BEESOFT</span>
                </div>
            </a>
        </li>
    </ul>
</div>
{{---------------- Fin enlaces destacados ----------------}}

{{-- Gestión documental --}}
<li style="display: flex;">
    <a href="{{ url('#') }}" style="display: flex; width: -webkit-fill-available;">
        <i class="fas fa-folder"></i>
        <span>Gestión documental</span>
    </a>
    <i onclick="toggleContent(this, '#showSubmenuGestionDocumental')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmenuGestionDocumental'" style="margin: 10px;"></i>
</li>

<div :id="'showSubmenuGestionDocumental'" class="collapse">
    <ul class="nav menuNavDesplegable">
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=CD_PANEL' }}" class="nav-link">
                <div>
                    <span class="mr-2">Consultar documentos digitalizados</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_descargas&view=category&id=15:tablas-de-valoracion-documental' }}" class="nav-link">
                <div>
                    <span class="mr-2">Tablas de Valoración Documental</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_descargas&view=category&id=11:dia-nacional-de-los-archivos&Itemid=313' }}" class="nav-link">
                <div>
                    <span class="mr-2">Actualizate en Archivos</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_descargas&view=category&id=12:inventarios-documentales&Itemid=313' }}" class="nav-link">
                <div>
                    <span class="mr-2">Inventarios Documentales</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_descargas&view=category&id=6:tablas-de-retencion-documental&Itemid=313' }}" class="nav-link">
                <div>
                    <span class="mr-2">Tablas de Retención Documental</span>
                </div>
            </a>
        </li>

    </ul>
</div>

{{-- Otros trámites y servicios --}}
<li style="display: flex;">
    <a href="{{ url('#') }}" style="display: flex; width: -webkit-fill-available;">
        <i class="fas fa-network-wired"></i>
        <span>Otros trámites y servicios</span>
    </a>
    <i onclick="toggleContent(this, '#showSubmenuOtherServices')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmenuOtherServices'" style="margin: 10px;"></i>
</li>

<div :id="'showSubmenuOtherServices'" class="collapse">
    <ul class="nav menuNavDesplegable">

        @if (Auth::user()->hasRole('Administrador de mantenimientos'))
            <li>
                <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=Panel' }}" class="nav-link">
                    <div>
                        <span class="mr-2">Mantenimientos de activos</span>
                    </div>
                </a>
            </li>
        @endif

        @if (Auth::user()->hasRole('Admin registro protocolo COVID-19'))
            <li>
                <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=Panel_gestion_registro_usuario_admin' }}" class="nav-link">
                    <div>
                        <span class="mr-2">Registro de usuarios</span>
                    </div>
                </a>
            </li>
        @endif

        @if (Auth::user()->hasRole('Operador registro protocolo COVID-19'))
            <li>
                <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=Panel_gestion_registro_usuario' }}" class="nav-link">
                    <div>
                        <span class="mr-2">Registro de usuarios (operador)</span>
                    </div>
                </a>
            </li>
        @endif

        @if (Auth::user()->hasRole('Autorizador COVID-19'))
            <li>
                <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=Panel_gestion_registro_autorizaciones' }}" class="nav-link">
                    <div>
                        <span class="mr-2">Registro de usuarios (autorizador)</span>
                    </div>
                </a>
            </li>
        @endif

        @if (Auth::user()->hasRole('Consulta protocolo COVID-19'))
            <li>
                <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=Panel_gestion_registro_usuario_consulta' }}" class="nav-link">
                    <div>
                        <span class="mr-2">Registro de usuarios (consulta)</span>
                    </div>
                </a>
            </li>
        @endif

        @if (Auth::user()->hasRole('Administrador Proveedores') || Auth::user()->hasRole('Líder de Proveedores'))
            <li>
                @if(Auth::user()->hasRole('Administrador Proveedores'))
                    <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=EPAPROV_PANEL' }}" class="nav-link">
                        <div>
                            <span class="mr-2">Proveedores</span>
                        </div>
                    </a>
                @elseif(Auth::user()->hasRole('Líder de Proveedores'))
                    <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=EPAPROV_PROVEEDORES' }}" class="nav-link">
                        <div>
                            <span class="mr-2">Proveedores</span>
                        </div>
                    </a>
                @endif
            </li>
        @endif

        @if (Auth::user()->hasRole('Udc administrador'))
            <li>
                <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=admin_images' }}" class="nav-link">
                    <div>
                        <span class="mr-2">Administración de imágenes TV</span>
                    </div>
                </a>
            </li>
        @endif

        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=Solicitudes_correspondencia&st=total&std=rbd' }}" class="nav-link">
                <div>
                    <span class="mr-2">Solicitudes internas</span>
                </div>
            </a>
        </li>

        @if (Auth::user()->hasRole('Leca Admin') || Auth::user()->hasRole('Leca Usuarios Nuevos'))
            <li>
                <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=lc_panel_admin' }}" class="nav-link">
                    <div>
                        <span class="mr-2">LECA módulo 1</span>
                    </div>
                </a>
            </li>
        @endif

        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=Documentos' }}" class="nav-link">
                <div>
                    <span class="mr-2">Sistema de gestión integrado</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=Solicitudes' }}" class="nav-link">
                <div>
                    <span class="mr-2">Solicitudes de acciones de calidad</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=CD_PANEL' }}" class="nav-link">
                <div>
                    <span class="mr-2">Consultar documentos digitalizados</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_descargas&view=category&id=15:tablas-de-valoracion-documental' }}" class="nav-link">
                <div>
                    <span class="mr-2">Tablas de Valoración Documental</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_descargas&view=category&id=11:dia-nacional-de-los-archivos&Itemid=313' }}" class="nav-link">
                <div>
                    <span class="mr-2">Actualizate en Archivos</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_descargas&view=category&id=12:inventarios-documentales&Itemid=313' }}" class="nav-link">
                <div>
                    <span class="mr-2">Inventarios Documentales</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_descargas&view=category&id=6:tablas-de-retencion-documental&Itemid=313' }}" class="nav-link">
                <div>
                    <span class="mr-2">Tablas de Retención Documental</span>
                </div>
            </a>
        </li>

    </ul>
</div>

{{-- Valida los roles para mostrar el módulo de Proceso precontractual --}}
@if(in_array('PC Gestor de recursos', $userRoles) ||
    in_array('PC Líder de proceso', $userRoles) ||
    in_array('PC Jefe de jurídica', $userRoles) ||
    in_array('PC Asistente de gerencia', $userRoles) ||
    in_array('PC Revisor de jurídico', $userRoles) ||
    in_array('PC Gestor planeación', $userRoles) ||
    in_array('PC Gestor presupuesto', $userRoles) ||
    in_array('PC Gerente', $userRoles) ||
    in_array('PC tesorero', $userRoles) ||
    in_array('PC jurídica especializado 3', $userRoles) ||
    in_array('PC director financiero', $userRoles) ||
    in_array('PC director jurídico', $userRoles))
    <!-- Admin acuerdos de pago -->
    <li style="display: flex;">
        <a href="#" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-address-card"></i>
            <span>Proceso precontractual</span>
        </a>
        <i onclick="toggleContent(this, '#showSubmemuPrecontractual')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmemuPrecontractual'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmemuPrecontractual'" class="collapse">
        <ul class="nav menuNavDesplegable">

            @if(in_array('PC Gestor de recursos', $userRoles) ||
                in_array('PC Líder de proceso', $userRoles) ||
                in_array('PC Jefe de jurídica', $userRoles) ||
                in_array('PC Asistente de gerencia', $userRoles) ||
                in_array('PC Revisor de jurídico', $userRoles) ||
                in_array('PC Gestor planeación', $userRoles) ||
                in_array('PC Gestor presupuesto', $userRoles) ||
                in_array('PC Gerente', $userRoles) ||
                in_array('PC tesorero', $userRoles) ||
                in_array('PC jurídica especializado 3', $userRoles) ||
                in_array('PC director financiero', $userRoles) ||
                in_array('PC director jurídico', $userRoles))
                <li>
                    <a href="{{ url('contractual-process/pc-previous-studies') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Estudios previos precontractual<br>(Fase 2)</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(in_array('PC Gerente', $userRoles))
                <li>
                    <a href="{{ url('contractual-process/controll-table') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Tablero de control</span>
                        </div>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif

{{-- Valida los roles para mostrar el módulo de Estructura Organizacional --}}
@if(in_array('Administrador intranet', $userRoles))
    {{-- Estructura Organizacional --}}
    <li style="display: flex;">
        <a href="{{ route('users.index') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-user"></i>
            <span>Estructura Organizacional</span>
        </a>
    </li>

@endif
@if (!$usuario->hasRole('Ciudadano') )


    @if (Auth::user()->hasRole('Administrador intranet'))
    {{-- Intranet --}}
    <li style="display: flex;">
        <a href="{{ url('intranet/notices') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-newspaper"></i>
            <span>Herramientas Colaborativas</span>
        </a>
    </li>
    @else
    {{-- Otra opción o mensaje --}}
    <li style="display: flex;">
        <a href="{{ url('intranet/notices-public') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-newspaper"></i>
            <span>Herramientas Colaborativas</span>
        </a>
    </li>
    @endif

@endif

<!-- Calidad -->
@if(config('app.mod_calidad'))
    <li style="display: flex;">
        <a href="{{ url('calidad/documentos-calidad') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-book"></i>
            <span>Calidad</span>
        </a>

        <i onclick="toggleContent(this, '#showSubmenuCalidad')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmenuCalidad'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmenuCalidad'" class="collapse">
        <ul class="nav menuNavDesplegable">
            <li>
                <a href="{{ url('calidad/documentos-calidad') }}" class="nav-link">
                    <div>
                        <span class="mr-2">Documentos</span>
                    </div>
                </a>
            </li>
            {{-- Valida si el usuario en sesión es un administrador de calidad --}}
            @if (Auth::user()->hasRole('Admin Documentos de Calidad'))
                <li>
                    <a href="{{ url('calidad/tipo-sistemas') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Tipos de sistemas</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('calidad/tipo-procesos') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Tipos de procesos</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('calidad/procesos') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Procesos</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('calidad/documento-tipo-documentos') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Tipos de documentos</span>
                        </div>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ url('calidad/documento-solicitud-documentals') }}" class="nav-link">
                    <div>
                        <span class="mr-2">Solicitudes de documentos</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ url('calidad/mapa-procesos-publico') }}" class="nav-link">
                    <div>
                        <span class="mr-2">Mapa de procesos</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
@endif

<!-- Planes de mejoramiento -->
@if(config('app.mod_planes_mejoramiento'))
    <li style="display: flex;">
        @php
            $rolesAdmin = [
                'Administración - Gestión (crear, editar y eliminar registros)',
                'Administración - Reportes',
                'Administración - Solo consulta'
            ];
            $rolesEval = [
                'Evaluadores - Gestión (crear, editar y eliminar registros)',
                'Evaluadores - Reportes',
                'Evaluadores - Solo consulta'
            ];
            $rolesPM = [
                'Planes de mejoramiento - Gestión (crear, editar y eliminar registros)',
                'Planes de mejoramiento - Reportes',
                'Planes de mejoramiento - Solo consulta'
            ];

            $url = null;

            if ($usuario->hasAnyRole($rolesAdmin)) {
                $url = url('improvement-plans/type-evaluations');
            } elseif ($usuario->hasAnyRole($rolesEval)) {
                $url = url('improvement-plans/evaluations');
            } elseif ($usuario->hasAnyRole($rolesPM)) {
                $url = url('improvement-plans/my-evaluations');
            }
        @endphp
        <a href="{{ $url }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-chart-line"></i>
            <span>Planes de mejoramiento</span>
        </a>

        <i onclick="toggleContent(this, '#showSubmenuPlanesDeMejoramiento')" class="fa fa-chevron-down" aria-hidden="true" data-toggle="collapse" :data-target="'#showSubmenuPlanesDeMejoramiento'" style="margin: 10px;"></i>
    </li>

    <div :id="'showSubmenuPlanesDeMejoramiento'" class="collapse">
        <ul class="nav menuNavDesplegable">
            {{-- Valida si tiene alguno de los roles de administración --}}
            @if($usuario->hasRole('Administración - Gestión (crear, editar y eliminar registros)') || 
                $usuario->hasRole('Administración - Reportes') || 
                $usuario->hasRole('Administración - Solo consulta') || 
                $usuario->hasRole('Registered'))
                <li>
                    <a href="{{ url('improvement-plans/type-evaluations') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Administración</span>
                        </div>
                    </a>
                </li>
            @endif
            {{-- Valida si tiene alguno de los roles de evaluadores --}}
            @if($usuario->hasRole('Evaluadores - Gestión (crear, editar y eliminar registros)') || 
                $usuario->hasRole('Evaluadores - Reportes') || 
                $usuario->hasRole('Evaluadores - Solo consulta') || 
                $usuario->hasRole('Registered'))
                <li>
                    <a href="{{ url('improvement-plans/evaluations') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Evaluadores</span>
                        </div>
                    </a>
                </li>
            @endif
            {{-- Valida si tiene alguno de los roles de planes de mejoramiento --}}
            @if($usuario->hasRole('Planes de mejoramiento - Gestión (crear, editar y eliminar registros)') || 
                $usuario->hasRole('Planes de mejoramiento - Reportes') || 
                $usuario->hasRole('Planes de mejoramiento - Solo consulta') || 
                $usuario->hasRole('Registered'))
                <li>
                    <a href="{{ url('improvement-plans/my-evaluations') }}" class="nav-link">
                        <div>
                            <span class="mr-2">Planes de mejoramiento</span>
                        </div>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif

{{-- Valida los roles para mostrar el módulo de Configuración --}}
@if(in_array('Administrador intranet', $userRoles))
    {{-- Configuración --}}
    <li style="display: flex;">
        <a href="{{ route('configuration-generals.index') }}" style="display: flex; width: -webkit-fill-available;">
            <i class="fa fa-cog"></i>
            <span>Configuración</span>
        </a>
    </li>
@endif


{{-- Correspondencia Recibida --}}
{{-- <li>
    <a href="{{ route('external-receiveds.index') }}" style="display: flex;">
        <i class="fa fa-mail-bulk"></i>
        <span>Correspondencia Recibida</span>
        <div v-if="dataForm.total_corr_recibida >= 0" v-text="'('+dataForm.total_corr_recibida+')'" style="float: right;"></div>
        <div class="loading" v-else>
            <div class="spinner" style="position: sticky; float: right; width: 18px; height: 18px;"></div>
        </div>
    </a>
</li> --}}

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
