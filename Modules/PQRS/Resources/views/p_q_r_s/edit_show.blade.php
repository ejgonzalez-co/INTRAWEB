
<!-- Panel Información ciudadano -->
<div class="card mb-3" data-sortable-id="ui-general-1">

    <div class="card-body">
        <h5 class="mb-2"><strong>Datos del remitente</strong></h5>
        <div class="d-flex gap-3">
            <div class="bg-gray bor rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">
                <img v-if="dataShow.ciudadano_users && dataShow.ciudadano_users.url_img_profile"
                    class="w-100 rounded-circle" :src="'{{ asset('storage') }}/' + dataShow.ciudadano_users.url_img_profile" alt="" />
                <div v-else>@{{ dataShow.nombre_ciudadano ? dataShow.nombre_ciudadano.charAt(0) : 'N' }}</div>

            </div>
            <div class="col">
                <div class="mb-3">
                    <dt>@lang('Nombre'):</dt>

                    <div class="d-flex align-items-center">
                        <i class="far fa-user"></i>
                        <dd class="mb-0 ml-2">@{{ dataShow.nombre_ciudadano ? dataShow.nombre_ciudadano : dataShow.ciudadano_users?.name ?? 'N/A' }}.</dd>
                    </div>
                </div>
                <div class="mb-3">

                    <dt>@lang('Correo'):</dt>

                    <div class="d-flex align-items-center">
                        <i class="far fa-envelope"></i>
                        <dd class="mb-0 ml-2">@{{ dataShow.email_ciudadano ? dataShow.email_ciudadano : dataShow.ciudadano_users?.email ?? 'NA' }}.</dd>
                    </div>
                </div>
                <div class="mb-3">

                    <dt>N° Documento:</dt>
                    <div class="d-flex align-items-center">
                        <i class="far fa-id-card"></i>
                        <dd class="mb-0 ml-2">@{{ dataShow.documento_ciudadano ?? 'NA'}}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Detalles de la PQRS --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5><strong>Detalles de la PQRS</strong></h5>
            <span class="rounded-pill border p-5">
                Estado: <strong>@{{ dataShow.estado ?? 'NA' }}</strong>
            </span>
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">Información</h6>
                <div class="flex-grow-1">
        
                    <dt>Contenido:</dt>
                    <dd class="mb-0 d-flex align-items-center mb-2" v-if="dataShow.contenido">
                        <span class="me-2 mr-2"><i class="fas fa-align-left"></i></span>
                        <span v-html="formatTextForHtml(dataShow.contenido)"></span>
                    </dd>
                    <div class="d-flex flex-column align-items-center justify-content-center h-70" v-else>
                        <i class="fas fa-exclamation-circle fa-4x text-secondary"></i>
                        <h6 class="mt-3 text-secondary">No hay contenido asociado</h6>
                    </div>
                    <dt>Folios:</dt>
                    <dd class="mb-0 d-flex align-items-center mb-2">
                        @{{ dataShow.folios ?? 'NA' }}
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body">
                <!-- Document pdf Field -->
                <dt class="mb-2">@lang('Documento principal del PQR'):</dt>
                <dd v-if="dataShow.document_pdf && dataShow.document_pdf.length > 0">

                    <viewer-attachement :display-flex="true" :link-file-name="true" v-if="dataShow.document_pdf" :list="dataShow.document_pdf "></viewer-attachement>

                </dd>
                <dd v-else>
                    <span>No tiene adjunto</span>
                </dd>
            </div>
        </div>
    </div>

    <div v-if="dataShow.pqr_correspondence" class="col-12 mb-3">
        <!-- Correspondencia recibida asociada -->
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <dt>Correspondencia recibida asociada:</dt>
                    <dd>
                        <a :href="'{{ url('/') }}/correspondence/external-receiveds?qder=' + dataShow.recibida_encrypted_id.id_correspondence_recibida">
                            @{{ dataShow.pqr_correspondence.consecutive }}
                        </a>
                    </dd>
                </div>

                <!-- Documento principal de la correspondencia -->
                <div class="mb-3">
                    <dt>Documento principal de la correspondencia:</dt>
                    <dd class="col-12" v-if="dataShow.pqr_correspondence.document_pdf && dataShow.pqr_correspondence.document_pdf.length > 0">
                        <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.pqr_correspondence.document_pdf"></viewer-attachement>
                    </dd>
                    <span v-else>
                        No tiene adjunto
                    </span>
                </div>

                <!-- Anexos de la correspondencia -->
                <div class="mb-3">
                    <dt>Anexos de la correspondencia:</dt>
                    <dd class="col-12" v-if="dataShow.pqr_correspondence.attached_document && dataShow.pqr_correspondence.attached_document.length > 0">
                        <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.pqr_correspondence.attached_document"></viewer-attachement>
                    </dd>
                    <dd v-else class="col-12">
                        <span>No tiene adjunto</span>
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body">
                <!-- Document pdf Field -->
                <dt class="mb-2">Anexos del ciudadano:</dt>
                <dd v-if="dataShow.adjunto_ciudadano && dataShow.adjunto_ciudadano.length > 0">
                    <viewer-attachement :display-flex="true" :link-file-name="true" v-if="dataShow.adjunto_ciudadano" :list="dataShow.adjunto_ciudadano"></viewer-attachement>
                </dd>
                <dd v-else>
                    No tiene adjunto
                </dd>
            </div>
        </div>
    </div>

    {{-- Información Básica --}}
    <div class="row g-3 align-items-stretch mr-1 ml-1">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">Información Básica</h6>
                    <div class="flex-grow-1 ">
                        <dt>No. Radicado:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            <span class="me-2 mr-2"><i class="fas fa-hashtag"></i></span> @{{ dataShow.pqr_id ?? 'NA' }}
                        </dd>
                        <dt>Tipo de solicitud:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            @{{ dataShow.pqr_tipo_solicitud?.nombre ?? 'NA' }}
                        </dd>
                        <dt>Eje temático:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            <span v-if="dataShow.pqr_eje_tematico_id">@{{ dataShow.pqr_eje_tematico?.nombre }}</span>
                            <span v-else>@{{ dataShow.nombre_ejetematico ?? 'NA' }}</span>
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">Canales</h6>
                    <div class="flex-grow-1">
                        <dt>Canal:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            @{{ dataShow.canal ?? 'NA' }}
                        </dd>
                        <dt>Anexos:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            @{{ dataShow.anexos ?? 'NA' }}
                        </dd>
                        <dt>Autorizo recibir respuesta por correo:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            <span class="me-2 mr-2"><i class="fas fa-envelope"></i></span> @{{ dataShow.respuesta_correo ? '@lang('yes')' : '@lang('no')' }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">Plazos</h6>
                    <div class="flex-grow-1">
                        <dt>Tipo plazo eje temático:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            <span class="me-2 mr-2"><i class="fas fa-clock"></i></span> @{{ dataShow.tipo_plazo ?? dataShow.pqr_eje_tematico?.plazo_unidad ?? 'N/A' }}
                        </dd>
                        <dt>Plazo eje temático:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            <span class="me-2 mr-2"><i class="fas fa-hourglass-half"></i></span> @{{ dataShow.plazo ?? dataShow.pqr_eje_tematico?.tipo_plazo ?? 'N/A' }}
                        </dd>
                        <dt>Días temprana:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            <span class="me-2 mr-2"><i class="fas fa-calendar-day"></i></span> @{{ dataShow.temprana ?? dataShow.pqr_eje_tematico?.temprana ?? 'N/A' }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">Fechas</h6>
                    <div class="flex-grow-1">
                        <dt>Fecha de recepción:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            <span class="me-2 mr-2"><i class="fas fa-calendar-minus"></i>
                            </span> @{{ dataShow.created_at }}
                        </dd>
                        <dt>Fecha de modificación:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            <span class="me-2 mr-2"><i class="fas fa-edit"></i></span> @{{ dataShow.updated_at }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Vencimiento</h6>
                    <div class="flex-grow-1 mt-3 mb-2">
                        <div class="row ml-2">
                            <div class="alert w-30" :class="{
                'estado_abierto': dataShow.estado == 'Abierto',
                'estado_cancelado': dataShow.estado == 'Cancelado',
                'estado_finalizado_a_tiempo': dataShow.estado == 'Finalizado' && dataShow.linea_tiempo == 'A tiempo',
                'estado_finalizado_vencido_justificado': dataShow.estado == 'Finalizado vencido justificado',
                'estado_a_tiempo': dataShow.linea_tiempo == 'A tiempo' && dataShow.estado != 'Finalizado',
                'estado_proximo_vencer': dataShow.linea_tiempo == 'Próximo a vencer',
                'estado_vencido': dataShow.linea_tiempo == 'Vencido'
            }" role="alert">
                                <dt>Fecha de vencimiento:</dt>
                                <dd>
                                    <span>@{{ dataShow.fecha_vence ? formatTextDate(dataShow.fecha_vence) : "N/A" }} <br>
                                        {{-- <span v-if="dataShow.fecha_vence"><strong>Tiempo restante hasta vencimiento: </strong>@{{ dataShow.dias_restantes ? dataShow.dias_restantes : dataShow.dias_restantes_pqr }}</span> --}}
                                    </span>
                                </dd>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-3">
        <!-- Información del correo integrado -->
        <div v-if="dataShow.pqr_correspondence && dataShow.pqr_correspondence.correo_integrado_datos" class="card mb-4">
            <div class="card-body">
                <div class="accordion" id="showIntegrateMail">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <span class="text-link" data-toggle="collapse" data-target="#correoIntegradoVerDetalles" aria-expanded="false" aria-controls="correoIntegradoVerDetalles">
                                    Ver información del correo integrado @{{ dataShow.pqr_correspondence.correo_integrado_datos.consecutivo ?? "" }}
                                    <i class="fas fa-chevron-down mr-2"></i>
                                </span>
                            </h5>
                        </div>

                        <div id="correoIntegradoVerDetalles" class="collapse" aria-labelledby="headingOne" data-parent="#showIntegrateMail">
                            <div class="card-body">
                                <!-- Asunto -->
                                <div class="mb-3">
                                    <dt>Asunto:</dt>
                                    <dd>@{{ dataShow.pqr_correspondence.correo_integrado_datos.asunto ?? 'NA' }}</dd>
                                </div>

                                <!-- Contenido -->
                                <div class="mb-3">
                                    <dt>Contenido:</dt>
                                    <dd v-html="dataShow.pqr_correspondence.correo_integrado_datos.contenido"></dd>
                                </div>

                                <!-- Documentos adjuntos -->
                                <div class="mb-3">
                                    <dt>Documentos:</dt>
                                    <dd class="col-12" v-if="dataShow.pqr_correspondence.correo_integrado_datos.adjuntos_correo && dataShow.pqr_correspondence.correo_integrado_datos.adjuntos_correo.length > 0">
                                        <viewer-attachement :display-flex="true" :list="dataShow.pqr_correspondence.correo_integrado_datos.adjuntos_correo"></viewer-attachement>
                                    </dd>
                                    <dd v-else class="col-12">
                                        <span>No tiene adjunto</span>
                                    </dd>
                                </div>

                                <!-- Enlace a más información del correo integrado -->
                                <div class="mb-3">
                                    <dt>Correo Integrado:</dt>
                                    <dd>
                                        <a :href="'/correspondence/correo-integrados?qderMailShra=' + dataShow.pqr_correspondence.correo_integrado_encrypted_id.correo_id" target="_blank">
                                            Ver más información del correo integrado <i class="fas fa-external-link-alt" style="color: #2196f3;"></i>
                                        </a>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
