<!-- Panel -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Datos generales del documento: @{{ dataShow.consecutivo }} <span v-if="dataShow.estado != 'Público'">(Consecutivo temporal)</span></strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Consecutivo Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Consecutivo'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.consecutivo }}.</dd>

            <!-- Titulo Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Título'):</dt>
            <dd class="col-3">@{{ dataShow.titulo }}.</dd>
        </div>

        <div class="row">
            <!-- Version Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Versión'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.version }}.</dd>

            <!-- Estado Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Estado'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.estado }}.</dd>
        </div>

        <div class="row">
            <!-- Distribucion Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Distribución'):</dt>
            <dd class="col-3">@{{ dataShow.distribucion }}.</dd>

            <!-- Tipo Sistema Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Tipo de sistema'):</dt>
            <dd class="col-3">@{{ dataShow.tipo_sistema?.nombre_sistema }}.</dd>
        </div>

        <div class="row">
            <!-- Tipo Documento Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Tipo de documento'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.tipo_documento }}.</dd>

            <!-- Proceso Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Proceso'):</dt>
            <dd class="col-3">@{{ dataShow.proceso ?? dataShow.documento_proceso?.nombre }}.</dd>
        </div>

        <div class="row">
            <!-- Formato Publicacion Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Formato de publicacion'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.formato_publicacion }}.</dd>
        </div>

        <div class="row">
            <!-- Document Pdf Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Documento principal'):</dt>
            <dd class="col-9 text-truncate">
                <viewer-attachement :list="dataShow.document_pdf" :key="dataShow.document_pdf"></viewer-attachement>
            </dd>
        </div>
    </div>
</div>

<!-- Panel -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title">Datos adicionales</h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Clase Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Clase'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.clase }}.</dd>

            <!-- Visibilidad Documento Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Visibilidad del documento'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.visibilidad_documento }}.</dd>
        </div>

        <div class="row">
            <!-- Separador Consecutivo Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Separador del consecutivo'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.separador_consecutivo }}</dd>

            <!-- Calidad Documento Solicitud Documental Id Field -->
            <dt class="text-inverse col-3 text-truncate" v-if="dataShow.documento_solicitud_documental">@lang('Solicitud documental asociada'):</dt>
            <dd class="col-3" v-if="dataShow.documento_solicitud_documental">@{{ dataShow.documento_solicitud_documental?.codigo }} - @{{ dataShow.documento_solicitud_documental?.nombre_documento }}.</dd>
        </div>
    </div>
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

<!-- Panel -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title">Usuarios que intervinieron en el documento</h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">
            <!-- Elaboro Nombres Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Elaboró'):</dt>
            <dd class="col-3">@{{ dataShow.elaboro_nombres }} (@{{ dataShow.elaboro_cargos }}).</dd>
        </div>

        <div class="row" v-if="dataShow.reviso_nombres">
            <!-- Reviso Nombres Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Revisó'):</dt>
            <dd class="col-3">@{{ dataShow.reviso_nombres }} (@{{ dataShow.reviso_cargos }}).</dd>
        </div>

        <div class="row" v-if="dataShow.aprobo_nombres">
            <!-- Aprobo Nombres Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Aprobó'):</dt>
            <dd class="col-3">@{{ dataShow.aprobo_nombres }} (@{{ dataShow.aprobo_cargos }}).</dd>
        </div>

        <div class="row" v-if="dataShow.publico_nombres">
            <!-- Publico Nombres Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Publicó'):</dt>
            <dd class="col-3">@{{ dataShow.publico_nombres }} (@{{ dataShow.publico_cargos }}).</dd>
        </div>
    </div>
</div>


<!-- Panel Flujo de producción documental -->
<div id="show_cards"  class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <button class="btn btn-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental
        </button>
        <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_cards')" title="Ver en tabla" style="margin-left: auto;">
            <i id="btnTable" class="fa fa-th" style="color: #5f6368;"></i>
        </button>
        <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'documento')" title="Descargar flujo documental">
            <i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"></i>
        </button>
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
                            <div class="timeline-article" v-for="(history, key) in dataShow.documento_historials">
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

                                            <strong>Título:</strong> @{{ history.titulo }}<br>
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
    <!-- end panel-body -->
</div>

<!-- Panel Flujo de producción documental -->
<div id="show_table"  class="panel" title="Ver en lista" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <button class="btn btn-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental
        </button>
        <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_table')" style="margin-left: auto;">
            <i id="btnCard" class="fas fa-square" style="color: #5f6368;"></i>
        </button>
        <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'documento')" title="Descargar flujo documental">
            <i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"></i>
        </button>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="collapse" id="collapseExample">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped fix-vertical-table"  >
                            <thead>
                                <tr class="font-weight-bold"  style="background-color: #00B0BD; color: white; text-align: center;vertical-align: middle;" >
                                    <th>Creado por</th>
                                    <th>@lang('Created_at')</th>
                                    <th>@lang('Consecutive')</th>
                                    <th>Tipo de documento</th>
                                    <th>@lang('Estado')</th>
                                    <th>@lang('Título')</th>
                                    <th>Proceso</th>
                                    <th>Historial</th>
                                    <th>Observación</th>
                                    <th>Archivos adjuntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(history, key) in dataShow.documento_historials">
                                    <td>@{{ history.users_name }}</td>
                                    <td>@{{ history.created_at }}</td>
                                    <td>@{{ history.consecutivo }}</td>
                                    <td>@{{ history.documento_tipo_documento?.nombre ?? "N/A" }}</td>
                                    <td>@{{ history.estado }}</td>
                                    <td>@{{ history.titulo }}</td>
                                    <td>@{{ history.proceso?.nombre }}</td>
                                    <td>@{{ history.observacion_historial }}</td>
                                    <td>@{{ history.observacion }}</td>
                                    <td>
                                        <ul v-if="history.document_pdf">
                                            <li v-for="document_pdf in history.document_pdf.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+document_pdf" target="_blank">Ver adjunto</a><br/></li>
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
    </div>
    <!-- end panel-body -->
</div>
