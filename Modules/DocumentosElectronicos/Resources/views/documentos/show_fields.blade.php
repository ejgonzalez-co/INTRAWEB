<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row" v-if="(dataShow.estado == 'Elaboración' || dataShow.estado == 'Revisión') && dataShow.users_id_actual != dataShow.elaboro_id">
        <!-- Columna izquierda -->
        <div class="d-flex flex-column col-12 col-lg-7 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos generales del documento electrónico: @{{ dataShow.consecutivo }} <span v-if="dataShow.estado != 'Público'">(Consecutivo temporal)</span></strong></h5>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>Tipo de documento:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.de_tipos_documentos?.nombre }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>@lang('Consecutivo')<span v-if="dataShow.estado != 'Público'"> temporal</span>:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.consecutivo }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>@lang('Título'):</strong></label><br>
                            <span class="mb-0">@{{ dataShow.titulo_asunto }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>Documento de Intraweb asociado:</strong></label><br>
                            <span class="mb-0" v-if="dataShow.documentos_asociados">
                                <a :href="'?qder=' + dataShow.id_encode">@{{ dataShow.documento_asociado.consecutivo }}</a>
                            </span>
                            <span class="mb-0" v-else>No tiene</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>Origen del documento:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.origen_documento }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-1"><strong>@lang('Estado'):</strong></label><br>
                            <span class="mb-0 text-center col"
                                :class="{
                                    'estado_elaboracion': dataShow.estado == 'Elaboración',
                                    'estado_publico': dataShow.estado == 'Público',
                                    'estado_revision': dataShow.estado == 'Revisión',
                                    'estado_pendiente_firma': dataShow.estado == 'Pendiente de firma',
                                    'estado_devuelto_modificar': dataShow.estado == 'Devuelto para modificaciones'
                                }">@{{ dataShow.estado }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha -->
        <div class="d-flex flex-column col-12 col-lg-5 mb-3" v-if="(dataShow.estado == 'Elaboración' || dataShow.estado == 'Revisión') && dataShow.users_id_actual != dataShow.elaboro_id">
            <!-- Panel Derecho (Datos de destino) -->
            <div class="card h-100" data-sortable-id="ui-general-1" v-if="(dataShow.estado == 'Elaboración' || dataShow.estado == 'Revisión') && dataShow.users_id_actual != dataShow.elaboro_id">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Usuario al que se le envió el documento a @{{ dataShow.estado }}</strong></h5>

                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="mb-0"><strong>Usuario actual con el documento:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.users_name_actual }}</span>
                        </div>

                        <div class="col-12 mb-2">
                            <label class="mb-0"><strong>Tipo de usuario:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.tipo_usuario }}</span>
                        </div>

                        <div class="col-12 mb-2">
                            <label class="mb-0"><strong>Correo del usuario:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.correo_usuario }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documento Principal -->
    <div class="row mb-3" v-if="(dataShow.estado == 'Elaboración' || dataShow.estado == 'Revisión') && dataShow.users_id_actual != dataShow.elaboro_id">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <label class="col-form-label col-md-2 text-black-transparent-7"><strong>Documento principal:</strong></label>
                    <div class="form-group col-md-10" v-if="dataShow.document_pdf">
                        <viewer-attachement :link-file-name="true" :list="dataShow.document_pdf" :key="dataShow.document_pdf"></viewer-attachement>
                    </div>

                    <label class="mb-0" v-else>No tiene adjunto</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos generales del documento electrónico: @{{ dataShow.consecutivo }} <span v-if="dataShow.estado != 'Público'">(Consecutivo temporal)</span></strong></h5>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>Tipo de documento:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.de_tipos_documentos?.nombre }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>@lang('Consecutivo')<span v-if="dataShow.estado != 'Público'"> temporal</span>:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.consecutivo }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>@lang('Título'):</strong></label><br>
                            <span class="mb-0">@{{ dataShow.titulo_asunto }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>Documento de Intraweb asociado:</strong></label><br>
                            <span class="mb-0" v-if="dataShow.documentos_asociados">
                                <a :href="'?qder=' + dataShow.id_encode">@{{ dataShow.documento_asociado.consecutivo }}</a>
                            </span>
                            <span class="mb-0" v-else>No tiene</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>Origen del documento:</strong></label><br>
                            <span class="mb-0">@{{ dataShow.origen_documento }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-1"><strong>@lang('Estado'):</strong></label><br>
                            <span class="mb-0 text-center col"
                                :class="{
                                    'estado_elaboracion': dataShow.estado == 'Elaboración',
                                    'estado_publico': dataShow.estado == 'Público',
                                    'estado_revision': dataShow.estado == 'Revisión',
                                    'estado_pendiente_firma': dataShow.estado == 'Pendiente de firma',
                                    'estado_devuelto_modificar': dataShow.estado == 'Devuelto para modificaciones'
                                }">@{{ dataShow.estado }}</span>
                        </div>

                        
                        <div class="col-12 col-md-6" v-if="dataShow.require_answer">
                            <!-- require_answer Field -->
                            <dt class="text-inverse text-left  ">Finaliza PQRS:</dt>
                            <dd class="">@{{ dataShow.require_answer ? dataShow.require_answer : 'No aplica' }}</dd>
                        </div>

                        <div class="col-12 col-md-6" v-if="dataShow.pqr_consecutive">
                            <!-- pqr_consecutive Field -->
                            <dt class="text-inverse text-left ">Respuesta a PQRS:</dt>
                            <dd v-if="dataShow.require_answer=='Si'">@{{ dataShow.pqr_consecutive }}</dd>
                            <dd v-else>No aplica</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-2"><strong>Documento principal</strong></h5>
                    <div class="form-group col-md-10" v-if="dataShow.document_pdf">
                        <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.document_pdf" :key="dataShow.document_pdf"></viewer-attachement>
                    </div>

                    <div class="d-flex flex-column align-items-center justify-content-center h-75"
                        v-else>
                        <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                        <h6 class="mt-3 text-secondary">No tiene adjunto</h6>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="row mb-3" v-if="dataShow.compartidos">
        <div class="col-12">
            <!-- Panel datos usuarios compartidos-->
            <div class="card" data-sortable-id="ui-general-1" >
                <div class="card-body">
                    <h5><strong>Datos de usuarios y dependencias compartidos</strong></h5>

                    <div class="table-responsive" v-if="dataShow.de_compartidos?.length > 0">
                        <table id="anotaciones" class="table table-bordered">
                            <thead>
                                <tr class="font-weight-bold">
                                    <td>Usuario</td>
                                    <td>Tipo de usuario</td>
                                    <td>Fecha</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="copy_share in dataShow.de_compartidos">
                                    <td>@{{ copy_share.nombre }}</td>
                                    <td>@{{ copy_share.categoria }}</td>
                                    <td>@{{ copy_share.created_at }}</td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3" v-if="dataShow.adjuntos">
        <div class="col">
            <!-- Panel adjuntos-->
            <div class="card" data-sortable-id="ui-general-1" >
                <div class="card-body">
                    <h5><strong>Archivos adjuntos</strong></h5>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <label><strong>Listado de
                                        adjuntos:</strong></label>
                                <div class="form-group col-md-10" v-if="dataShow.adjuntos">
                                    <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.adjuntos" :key="dataShow.adjuntos"></viewer-attachement>
                                </div>
                                <label class="col-form-label col-md-8" v-else>No tiene adjuntos</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>
        </div>
    </div>
    <div class="row mb-3" v-if="dataShow.de_documento_has_de_metadatos?.length > 0">
        <div class="col-12">
            {{-- Detalles de los metadatos y sus respuestas --}}
            <div class="card" data-sortable-id="ui-general-1" >
                <!-- Panel metadatos valores -->
                <div class="card-body">
                    <h5><strong>Metadatos</strong></h5>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3" v-for="metadato in dataShow.de_documento_has_de_metadatos">
                            <div class="form-group">
                                <label><strong>@{{ metadato.de_metadatos.nombre_metadato }}: </strong></label>
                                <label>@{{ metadato.valor }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card" data-sortable-id="ui-general-1">
                <div class="card-body">
                    <h5 class="mb-2"><strong>Seguimiento del documento</strong></h5>

                    <div><strong>Anotaciones: </strong></div>
                    <table id="anotaciones" class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold" style="background: #00b0bd;
                                    color: white;">
                                <td>Fecha</td>
                                <td>Usuario</td>
                                <td>Anotación</td>
                                <td>Adjuntos</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="anotacion in dataShow.documento_anotacions">
                                <td>@{{ anotacion.created_at }}</td>
                                <td>@{{ anotacion.nombre_usuario }}</td>
                                <td v-html="anotacion.contenido"></td>
                                <td v-if="anotacion.adjuntos">
                                    <viewer-attachement :link-file-name="true" :list="anotacion.adjuntos" :key="anotacion.adjuntos"></viewer-attachement>
                                </td>
                                <td v-else>No tiene adjuntos</td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                    <div><strong>¿Quiénes han leido el documento electrónico?</strong></div>
                    <table id="anotaciones" class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold">
                                <td>Usuario</td>
                                <td>Tipo de usuario</td>
                                <td>Accesos</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="leido in dataShow.documento_leido">
                                <td>@{{ leido.nombre_usuario }}</td>
                                <td>@{{ leido.tipo_usuario }}</td>
                                <td width="250">
                                    <i class="fa fa-arrow-circle-down" aria-hidden="true" data-toggle="collapse"
                                        :data-target="'#learnMore' + leido.id"></i>
                                    <div :id="'learnMore' + leido.id" class="collapse" v-html="leido.accesos"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <div class="row mb-3" v-if="dataShow.origen_documento == 'Producir documento en línea a través de Intraweb' && dataShow.de_documento_versions.length > 0">
        <div class="col-12">
            <!-- Panel versiones-->
            <div class="card" data-sortable-id="ui-general-1">
                <div class="card-body">
                    <h5><strong>Seguimiento al proceso de firma conjunta</strong></h5>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <div v-for="(version, key) in dataShow.de_documento_versions" class="card mb-3">
                                    <div class="card-body">
                                        <h6><strong>Versión @{{ version.numero_version }}</strong></h6>
                                        <table class="table table-hover fix-vertical-table mt-4" style="border-bottom: 2px solid #021E7F;">
                                            <thead>
                                                <tr>
                                                    <th>Número de versión</th>
                                                    <th>Creado por</th>
                                                    <th>Documento</th>
                                                    <th>Fecha de creación</th>
                                                    <th>Estado</th>
                                                    <th>Observación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>@{{ version.numero_version }}</td>
                                                    <td>@{{ version.nombre_usuario }}</td>
                                                    <td>
                                                        <a class="col-sm-9 col-md-9 col-lg-9"
                                                            @click="getPathDocument(version.document_pdf_temp, true);"
                                                            href="javascript:;">Ver documento</a>
                                                        <div v-if="routeLoading" class="spinner ml-2" style="position: sticky; float: right; right: 0; width: 15px; height: 15px; margin-bottom: 3px;"></div>
                                                    </td>
                                                    <td>@{{ version.created_at }}</td>
                                                    <td>@{{ version.estado }}</td>
                                                    <td>@{{ version.observacion }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6"><strong>Funcionarios para firma versión @{{ version.numero_version }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th>Tipo de usuario</th>
                                                                    <th>Funcionario</th>
                                                                    <th>Correo electrónico</th>
                                                                    <th>Estado</th>
                                                                    <th>Observación</th>
                                                                    @if(Auth::check() && Auth::user()->hasRole("Admin Documentos Electrónicos"))
                                                                    <th>Código de acceso al documento</th>
                                                                    <th>Enlace del documento para firmar</th>
                                                                    <th>Acciones</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(sign, key) in version.de_documento_firmars">
                                                                    <td>@{{ sign.created_at }}</td>
                                                                    <td>@{{ sign.tipo_usuario }}</td>
                                                                    <td>@{{ sign.nombre_usuario }}</td>
                                                                    <td>@{{ sign.correo }}</td>
                                                                    <td>@{{ sign.estado }}</td>
                                                                    <td>@{{ sign.observacion }}</td>
                                                                    @if(Auth::check() && Auth::user()->hasRole("Admin Documentos Electrónicos"))
                                                                    <td>@{{ sign.tipo_usuario == 'Externo' ? sign.codigo_acceso_documento : 'N/A' }}</td>
                                                                    <td v-if="sign.estado == 'Pendiente de firma' && sign.tipo_usuario == 'Externo'">
                                                                        <a @click="$refs.documentos_ref.copiarEnlace($event)" :href="'/documentos-electronicos/validar-codigo/'+sign.id_encriptado">Copiar enlace</a>
                                                                    </td>
                                                                    <td v-else>No disponible</td>
                                                                    <td>
                                                                        <span v-if="sign.tipo_usuario == 'Externo' && sign.estado == 'Pendiente de firma'">
                                                                            <button v-if="!$refs.documentos_ref.enviandoCorreo" @click="callFunctionComponent('documentos_ref', 'reenviarCorreoUsuarioExterno', sign)" class="btn btn-white btn-icon btn-md" title="Reenviar correo para firmar documento"><i class="fas fa-envelope"></i></button>
                                                                            <div v-else class="spinner" style="position: sticky; float: inline-start; width: 18px; height: 18px; margin: 4px;"></div>
                                                                        </span>
                                                                    </td>
                                                                    @endif
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> <!-- Cierre de cada card para versión -->
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Cierre de card principal -->
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-12">

            <!-- Panel Historial de firmas -->
            <div class="card" data-sortable-id="ui-general-1" v-if="dataShow.origen_documento == 'Producir documento en línea a través de Intraweb' && dataShow.de_documento_firmars.length > 0 && dataShow.see_conjunt_signatures">

                <div class="card-body">
                    <h5><strong>Firmas del documento</strong></h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover fix-vertical-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Created_at')</th>
                                            <th>Creado por</th>
                                            <th>ID firma</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(history, key) in dataShow.de_documento_firmars">
                                            <td>@{{ history.fecha_firma }}</td>
                                            <td>@{{ history.nombre_usuario }}</td>
                                            <td>@{{ history.hash }}</td>
                                            <td>@{{ history.ip }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">

            <!-- Panel Flujo de producción documental -->
            <div id="show_cards" class="card" data-sortable-id="ui-general-1">
                <div class="card-body">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle mb-2">
                        <button class="btn btn-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
                        <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_cards')" title="Ver en tabla" style="margin-left: auto;"><i id="btnTable" class="fa fa-th" style="color: #5f6368;"></i></button>
                        <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'documento')" title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"> </i> </button>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="collapse" id="collapseExample">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <!-- Vertical Timeline -->
                                    <section id="conference-timeline">

                                        <div class="timeline-start">Inicio</div>
                                        <div class="conference-center-line"></div>

                                        <div class="conference-timeline-content">
                                            <!-- Article -->

                                            <div class="timeline-article" v-for="(history, key) in dataShow.de_documento_historials">

                                                <div style="cursor: pointer;" data-toggle="collapse" data-target="#historial_completo"
                                                    v-bind:class="{'content-left-container': key % 2 === 0, 'content-right-container': key % 2 !== 0}">

                                                    <div v-bind:class="{'content-left': key % 2 === 0, 'content-right': key % 2 !== 0}">
                                                        <div style="display: flex; align-items: center;">
                                                            <div class="profile-image-container">
                                                                <img v-if="history.users && history.users.url_img_profile !== '' && history.users.url_img_profile !== 'users/avatar/default.png'"
                                                                    :src="'{{ asset('storage') }}/' + history.users.url_img_profile"
                                                                    alt="" class="profile-image">
                                                                <img v-else src="{{ asset('assets/img/user/profile.png') }}"
                                                                    alt="" class="profile-image">
                                                            </div>
                                                            <span class="timeline-author"> @{{ history.users_name }}</span>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <strong style="color:#00B0BD ">@{{ key + 1 }}. @{{ history.observacion_historial }}</strong> <br>
                                                            <strong>Observación:</strong> @{{ history.observacion ? history.observacion : 'N/A' }}<br>
                                                            <strong>Fecha y hora:</strong> @{{ history.date_format_day }} de
                                                            @{{ history.date_format_month_completo }} de @{{ history.date_format_year }} @{{ history.date_format_hour }}<br>

                                                            <strong>Título:</strong> @{{ history.titulo_asunto }}<br>
                                                            <strong>Usuarios compartidos del documento:</strong> <span
                                                                v-html="history.recipients ? history.recipients : 'N/A'"></span><br>

                                                            <strong>Documento:</strong>
                                                            <span v-if="history.document_pdf">
                                                                <span v-for="document_pdf in history.document_pdf.split(',')"
                                                                    style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 "
                                                                        :href="'{{ asset('storage') }}/' + document_pdf"
                                                                        target="_blank">Ver
                                                                        documento</a><br /></span>
                                                            </span>
                                                            <span v-else>
                                                                <span>No tiene documento</span>
                                                            </span>
                                                            <br>
                                                        <div class="row">
                                                            <strong class="col-md-2">Estado:</strong>
                                                            <div class="text-center  col-md-6"
                                                                :class="{
                                                'estado_elaboracion': history.estado == 'Elaboración',
                                                'estado_publico': history.estado == 'Público',
                                                'estado_revision': history.estado == 'Revisión' || history.estado == 'Revisión (Editado por externo)',
                                                'estado_pendiente_firma': history.estado == 'Pendiente de firma',
                                                'estado_devuelto_modificar': history.estado == 'Devuelto para modificaciones'
                                            }"> @{{ history.estado }} </div>
                                                        </div>

                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="meta-date">
                                                    <span class="date">@{{ history.date_format_day }}</span>
                                                    <span class="month">@{{ history.date_format_month }}</span>
                                                </div>
                                            </div>
                                            <!-- // Article -->

                                        </div>
                                        <div class="timeline-end">Última actualización</div>
                                    </section>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end panel-body -->
            </div>
            <div id="show_table" class="card" title="Ver en lista" data-sortable-id="ui-general-1">
                <div class="card-body">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle mb-2">
                        <button class="btn btn-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
                        <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_table')" style="margin-left: auto;"><i id="btnCard" class="fas fa-square" style="color: #5f6368;"></i></button>
                        <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'documento')" title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"> </i> </button>

                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="collapse" id="collapseExample">

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped fix-vertical-table">

                                            <thead>
                                                <tr class="font-weight-bold" style="background-color: #00B0BD; color: white; text-align: center;vertical-align: middle;">
                                                    <th>Creado por</th>
                                                    <th>@lang('Created_at')</th>
                                                    <th>@lang('Consecutive')</th>
                                                    <th>Tipo de documento</th>
                                                    <th>@lang('State')</th>
                                                    <th>@lang('Title')</th>
                                                    <th>Compartidos</th>
                                                    <th>Historial</th>
                                                    <th>Observación</th>
                                                    <th>Archivos adjuntos</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(history, key) in dataShow.de_documento_historials">
                                                    <td>@{{ history.users_name }}</td>
                                                    <td>@{{ history.created_at }}</td>
                                                    <td>@{{ history.consecutivo }}</td>
                                                    <td>@{{ history.de_tipos_documentos.nombre ?? "N/A" }}</td>
                                                    <td>@{{ history.estado }}</td>
                                                    <td>@{{ history.titulo_asunto }}</td>
                                                    <td>@{{ history.compartidos }}</td>
                                                    <td>@{{ history.observacion_historial }}</td>
                                                    <td>@{{ history.observacion }}</td>

                                                    <td>
                                                        <ul v-if="history.document_pdf">
                                                            <li v-for="document_pdf in history.document_pdf.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+document_pdf" target="_blank">Ver adjunto</a><br /></li>
                                                        </ul>
                                                        <ul v-else>
                                                            <li>No tiene adjuntos</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end panel-body -->

                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Panel Flujo de producción documental -->


</div>






{{-- Detalles de clasificacion documental --}}
<div class="panel" data-sortable-id="ui-general-1" id="clasificacion">
    <!-- Panel clasificación documental -->
    @if (isset($clasificacion) && $clasificacion === 'si')
    <div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Clasificación documental</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Oficina productora: </strong></label>
                        <label class="col-form-label col-md-8">@{{ dataShow.oficina_productora_clasificacion_documental?.nombre ?? 'No asignada' }}</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Serie: </strong></label>
                        <label class="col-form-label col-md-8">@{{ dataShow.serie_clasificacion_documental?.name_serie ?? 'No asignada' }}</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Subserie: </strong></label>
                        <label class="col-form-label col-md-8">@{{ dataShow.subserie_clasificacion_documental?.name_subserie ?? 'No asignada' }}</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- end panel-body -->
    </div>
    @endif
</div>

<!-- Panel Seguimiento al trámite -->