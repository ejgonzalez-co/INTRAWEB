<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row">

        <!-- Panel datos origen (más grande) -->
        <div class="d-flex flex-column mb-3 col-12 col-lg-7">

            <!-- Panel Información ciudadano -->
            <div class="card col-md-12  h-100" data-sortable-id="ui-general-1">

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
        </div>

        <!-- Panel datos destino (más pequeño) -->
        <div class="d-flex flex-column col-12 col-lg-5">
            <!-- Primera tarjeta: Datos de Destino -->

            <!-- Panel Información destino -->
            <div class="card mb-3 h-100" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="card-body">
                    <h5 class="mb-2"><strong>Datos de destino</strong></h5>

                    <div class="d-flex gap-3">
                        <div class="bg-gray bor rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">                            <img v-if="dataShow.funcionario_users && dataShow.funcionario_users.url_img_profile"
                                class="w-100 rounded-circle" :src="'{{ asset('storage') }}/' + dataShow.funcionario_users.url_img_profile" alt="" />
                            <div v-else>
                                @{{ dataShow.funcionario_destinatario ? dataShow.funcionario_destinatario.charAt(0) : 'N' }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <dt>@lang('Nombre'):</dt>

                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-tie"></i>
                                    <dd class="mb-0 ml-2">@{{ dataShow.funcionario_destinatario ?? 'N/A' }}.</dd>
                                </div>
                            </div>
                            <div class="mb-3">
                                <dt>@lang('Dependencia'):</dt>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building"></i>

                                    <dd class="mb-0 ml-2">@{{ dataShow.funcionario_users?.dependencies?.nombre  ?? 'NA'}}.</dd>

                                </div>

                            </div>
                        </div>
                        <br />


                        {{-- <div class="row">
                                                    <!-- Ciudadano Users Id Field -->
                                                    <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Listado de funcionarios con copia:</dt>
                                                    <dd class="col-sm-3 col-md-3 col-lg-3" v-if='!dataShow.pqr_copia || !dataShow.pqr_copia.length'>
                                                        No tiene copias
                                                    </dd>
                                                    <dd class="col-sm-3 col-md-3 col-lg-3" v-else>
                                                    <ul class="col-form-label col-md-8">
                                                            <li v-for="(copy_share, key) in dataShow.pqr_copia" :key="key">@{{ copy_share.name }} - @{{ copy_share.tipo }}</li>
                        </ul>
                        </dd>

                        <!-- Ciudadano Users Id Field -->
                        <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Correspondencia compartida con:</dt>
                        <dd class="col-sm-3 col-md-3 col-lg-3" v-if='!dataShow.pqr_compartida || !dataShow.pqr_compartida.length'>
                            Esta correspondecia no ha sido compartida
                        </dd>
                        <dd class="col-sm-3 col-md-3 col-lg-3" v-else>
                            <ul class="col-form-label col-md-8">
                                <li v-for="(copy_share, key) in dataShow.pqr_compartida" :key="key">@{{ copy_share.name }} - @{{ copy_share.tipo }}</li>
                            </ul>
                        </dd>
                    </div> --}}
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

    <div class="col-md-12 mb-3">
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

    <div class="col-12 mb-3" v-if="dataShow.id_correspondence || dataShow.adjunto_correspondence">
        <div class="card">
            <div class="card-body">
                <!-- Consecutivo de la correspondencia -->
                <div class="mb-3">
                    <dt>Consecutivo de la correspondencia:</dt>
                    <dd>@{{ dataShow.id_correspondence ?? 'NA' }}</dd>
                </div>

                <!-- Documento de la correspondencia -->
                <div class="mb-3">
                    <dt>Documento de la correspondencia:</dt>
                    <dd class="col-12" v-if="dataShow.adjunto_correspondence && dataShow.adjunto_correspondence.length > 0">
                        <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.adjunto_correspondence"></viewer-attachement>
                    </dd>
                    <span v-else>
                        No tiene adjunto
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12" v-if="dataShow.pqr_correspondence">
        <!-- Correspondencia recibida asociada -->
        <div class="card mb-3">
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
        
    {{-- Anexos del ciudadano: --}}
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
                    <h6 class="card-title">Otros datos</h6>
                    <div class="flex-grow-1">
                        <dt>Número de matrícula:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            @{{ dataShow.no_matricula ?? 'NA' }}
                        </dd>
                        <dt>Dirección del predio:</dt>
                        <dd class="mb-0 d-flex align-items-center mb-2">
                            @{{ dataShow.direccion_predio ?? 'NA' }}
                        </dd>
                        <dt>Motivos o hechos:</dt>
                        <dd style="text-align: justify" class="mb-0 d-flex align-items-center mb-2" v-html="formatTextForHtml(dataShow.motivos_hechos)"></dd>
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

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5><strong>Listado de funcionarios con copia o compartidos: </strong></h5>
                <span v-if="dataShow.pqr_copia_copmpartida?.length == 0">Esta PQR no ha sido compartida o enviada a copia.</span>

                <div class="table-responsive" v-if="dataShow.pqr_copia_copmpartida?.length > 0">
                    <table id="anotaciones" class="table table-bordered">
                        <thead>
                            <tr class="custom-thead">
                                <td>Usuario</td>
                                <td>Copia/Compartido</td>
                                <td>Fecha</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="copy_share in dataShow.pqr_copia_copmpartida">
                                <td>@{{ copy_share.name }}</td>
                                <td>@{{ copy_share.tipo }}</td>
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
<!-- Información PQR -->
<div class="card mb-3">
    <div class="card-body">
        <h5><strong>Gestión del trámite</strong></h5>

        <!-- Estado Finalizado -->
        <div class="row" v-if="dataShow.estado == 'Finalizado'">
            <div class="col-12">
                <dt class="text-inverse">@lang('Tipo de finalizacion'):</dt>
                <dd>@{{ dataShow.tipo_finalizacion ?? 'NA' }}.</dd>
            </div>
            <div class="col-12" v-if="dataShow.tipo_finalizacion == 'PQRS para trasladar a otra entidad'">
                <dt class="text-inverse">@lang('Empresa a la que traslada'):</dt>
                <dd>@{{ dataShow.empresa_traslado ?? 'NA' }}.</dd>
            </div>
        </div>

        <!-- Descripción del trámite -->
        <div class="row">
            <div class="col-12">
                <dt class="text-inverse">@lang('Descripción del trámite'):</dt>
                <dd>@{{ dataShow.descripcion_tramite ?? 'NA' }}.</dd>
            </div>
        </div>

        <hr />

        <!-- Respuesta parcial -->
        <div class="row">
            <div class="col-12" v-if="dataShow.respuesta_parcial">
                <dt class="text-inverse">@lang('Información de respuesta parcial'):</dt>
                <dd>@{{ dataShow.respuesta_parcial ?? 'NA' }}.</dd>
            </div>
            <div class="col-12">
                <dt class="text-inverse">@lang('Fecha de respuesta parcial'):</dt>
                <dd v-if="dataShow.fecha_fin_parcial">@{{ dataShow.fecha_fin_parcial }}.</dd>
                <dd v-else>N/A.</dd>
            </div>
            <div class="col-12">
                <dt class="text-inverse">Adjunto respuesta parcial:</dt>
                <dd v-if="dataShow.adjunto_r_parcial && dataShow.adjunto_r_parcial.length > 0">
                    <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.adjunto_r_parcial"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjuntos</span>
                </dd>
            </div>
        </div>

        <hr />

        <!-- Correspondencia externa -->
        <div class="row" v-if="dataShow.pqr_correspondence_external">
            <div class="col-12 mb-3">
                <dt class="text-inverse">Correspondencia enviada asociada:</dt>
                <a :href="'{{ url('/') }}/correspondence/externals?qder='+dataShow.external_encrypted_id.id_correspondence_external">
                    @{{ dataShow.pqr_correspondence_external.consecutive }}.
                </a>
            </div>
            <div class="col-12 mb-3">
                <dt class="text-inverse">Documento principal de la correspondencia enviada:</dt>
                <dd v-if="dataShow.pqr_correspondence_external.document_pdf && dataShow.pqr_correspondence_external.document_pdf.length > 0">
                    <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.pqr_correspondence_external.document_pdf"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjunto</span>
                </dd>
            </div>
        </div>

        <hr />

        <!-- Documento electrónico -->
        <div class="row" v-if="dataShow.pqr_documento_electronico">
            <div class="col-12 mb-3">
                <dt class="text-inverse">Documento electrónico asociado:</dt>
                <a :href="'{{ url('/') }}/documentos-electronicos/documentos?qder='+dataShow.documento_electronico_encrypted_id.id_documento_electronico">
                    @{{ dataShow.pqr_documento_electronico.consecutivo }}.
                </a>
            </div>
            <div class="col-12 mb-3">
                <dt class="text-inverse">Documento principal del documento electrónico:</dt>
                <dd v-if="dataShow.pqr_documento_electronico.document_pdf && dataShow.pqr_documento_electronico.document_pdf.length > 0">
                    <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.pqr_documento_electronico.document_pdf"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjunto</span>
                </dd>
            </div>
        </div>

        <br />

        <!-- Respuesta -->
        <div class="row">
            <div class="col-12">
                <dt class="text-inverse">@lang('Respuesta'):</dt>
                <dd>@{{ dataShow.respuesta ?? 'NA' }}.</dd>
            </div>
        </div>

        <!-- Oficios de respuesta y solicitud -->
        <div class="row">
            <div class="col-12">
                <dt class="text-inverse">@lang('No. De oficio de respuesta'):</dt>
                <dd>@{{ dataShow.no_oficio_respuesta ?? 'NA' }}.</dd>
            </div>
            <div class="col-12">
                <dt class="text-inverse">@lang('Adjunto oficio de respuesta'):</dt>
                <dd v-if="dataShow.adj_oficio_respuesta && dataShow.adj_oficio_respuesta.length > 0">
                    <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.adj_oficio_respuesta"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjuntos</span>
                </dd>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <dt class="text-inverse">@lang('N. De oficio de solicitud'):</dt>
                <dd>@{{ dataShow.no_oficio_solicitud ?? 'NA' }}.</dd>
            </div>
            <div class="col-12">
                <dt class="text-inverse">@lang('Adjunto oficio solicitud'):</dt>
                <dd v-if="dataShow.adj_oficio_solicitud && dataShow.adj_oficio_solicitud.length > 0">
                    <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.adj_oficio_solicitud"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjuntos</span>
                </dd>
            </div>
        </div>

        <hr />

        <!-- Archivos adjuntos -->
        <div class="row">
            <div class="col-12">
                <dt class="text-inverse">@lang('Archivos adjuntos'):</dt>
                <dd v-if="dataShow.adjunto && dataShow.adjunto.length > 0">
                    <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.adjunto"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjuntos</span>
                </dd>
            </div>
        </div>

        <!-- Documento PQR finalizada -->
        <div class="row" v-if="!dataShow.adjunto_finalizado_visible">
            <div class="col-12">
                <dt class="text-inverse">Documento PQR finalizada:</dt>
                <dd v-if="dataShow.adjunto_finalizado && dataShow.adjunto_finalizado.length > 0">
                    <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.adjunto_finalizado"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjuntos</span>
                </dd>
            </div>
        </div>

    </div>
</div>


@if (isset($clasificacion) && $clasificacion === 'si')
<div class="row mb-3">
    <div class="col-12">
        <!-- Panel clasificación documental -->
        <div class="card" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="card-body">
                <h5><strong>Clasificación documental</strong></h5>


                <div>
                    <label><strong>Serie: </strong></label>
                    <label>@{{ dataShow.serie_clasificacion_documental?.name_serie ?? 'No asignada' }}</label>
                </div>

                <div>
                    <label><strong>Subserie: </strong></label>
                    <label>@{{ dataShow.subserie_clasificacion_documental?.name_subserie ?? 'No asignada' }}</label>
                </div>

                <div>
                    <label><strong>Oficina productora: </strong></label>
                    <label>@{{ dataShow.oficina_productora_clasificacion_documental?.nombre ?? 'No asignada' }}</label>
                </div>
            </div>
            <!-- end panel-body -->
        </div>
    </div>
</div>
@endif

<!-- Panel expedientes relacionados -->

@if (config('app.mod_expedientes')) 
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class=""><strong>Expedientes relacionados</strong></h5>
                    <div v-if="dataShow.tiene_expediente?.length > 0">
                        <div class="row g-3">
                            <div class="col-12 col-md-4" v-for="expediente in dataShow.tiene_expediente" :key="expediente.id">
                                <p class="mb-2">
                                    @php
                                        $isLoggedIn = Auth::check();
                                        $userId = Auth::id(); // devuelve null si no hay usuario
                                    @endphp

                                    <a v-if="@json($isLoggedIn) && (expediente.permiso_usar_expediente || expediente.permiso_consultar_expediente || expediente.id_responsable == @json($userId))" 
                                    target="_blank" 
                                    :href="'/expedientes-electronicos/documentos-expedientes?c='+expediente.id_encoded" 
                                    style="color: rgb(72, 142, 241); font-weight: bold; text-decoration: underline;" 
                                    :title="expediente.nombre_expediente">
                                        @{{ expediente.consecutivo }}
                                    </a>
                                    <span v-else>@{{ expediente.consecutivo }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" v-else>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75">
                            <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                            <h6 class="text-secondary mt-3">No hay expedientes asociados.</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


<div class="row mb-3">
    <div class="col-12">

        <!-- Panel Devoluciones y cancelaciones -->
        <div class="card col-md-12" data-sortable-id="ui-general-1">

            <div class="card-body">
                <h5 class="mb-3"><strong>Devoluciones y cancelación</strong></h5>

                <!-- Panel Devolución interna -->
                <div class="card mb-2" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="card-body">
                        <h6 class="text-center"><strong>Devolución interna</strong></h6>
                        <hr>

                        <div class="">
                            <!-- Devolucion Field -->
                            <dt>@lang('Razón de la devolucion'):</dt>
                            <dd v-if="dataShow.devolucion">@{{ dataShow.devolucion ?? 'NA'}}.</dd>
                            <dd v-else>No hay devolución interna.</dd>
                        </div>
                    </div>
                </div>
                <!-- Panel Devolución a ciudadano -->
                <div class="card col-md-12 mb-2" data-sortable-id="ui-general-1">

                    <div class="card-body">
                        <h6 class="text-center"><strong>Devolución a ciudadano</strong></h6>
                        <hr>

                        <div class="">
                            <!-- Pregunta Ciudadano Field -->
                            <dt>@lang('Pregunta al ciudadano'):</dt>
                            <dd v-if="dataShow.pregunta_ciudadano">@{{ dataShow.pregunta_ciudadano ?? 'NA' }}.</dd>
                            <dd v-else>No hay devolución al ciudadano.</dd>
                        </div>

                        <div class="">
                            <!-- Respuesta Ciudadano Field -->
                            <dt>@lang('Respuesta del ciudadano'):</dt>
                            <dd v-if="dataShow.respuesta_ciudadano">@{{ dataShow.respuesta_ciudadano  ?? 'NA'}}.</dd>
                            <dd v-else>No hay devolución al ciudadano.</dd>
                        </div>

                        <div class="">

                            <!-- Document pdf Field -->
                            <dt>Documento de espera de respuesta:</dt>
                            <dd class="col-12" v-if="dataShow.adjunto_espera_ciudadano && dataShow.adjunto_espera_ciudadano.length > 0">
                                <viewer-attachement :display-flex="true" :link-file-name="true" v-if="dataShow.adjunto_espera_ciudadano" :list="dataShow.adjunto_espera_ciudadano"></viewer-attachement>
                            </dd>
                            <dd v-else>
                                <span>No tiene adjunto</span>
                            </dd>
                        </div>

                    </div>
                </div>

                <!-- Panel Cancelación -->
                <div class="card col-md-12 mb-2" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="card-body">
                        <h6 class="text-center"><strong>Cancelación</strong></h6>
                        <hr>

                        <div class="row">
                            <!-- Cancelación Field -->
                            <dt>@lang('Razón de la cancelación'):</dt>
                            <dd v-if="dataShow.estado == 'Cancelado'">@{{ dataShow.respuesta  ?? 'NA'}}.</dd>
                            <dd v-else>No existe cancelación.</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-12">


        <!-- Panel Seguimiento al trámite -->
        <div class="card col-md-12" data-sortable-id="ui-general-1">

            <div class="card-body">
                <h6><strong>Seguimiento al trámite</strong></h6>

                <div><strong>Anotaciones: </strong></div>
                <table id="anotaciones" class="table table-bordered text-center mt-2">
                    <thead>
                        <tr class="custom-thead">
                            <td>Fecha</td>
                            <td>Usuario</td>
                            <td>Anotación</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="anotacion in dataShow.pqr_anotacions">
                            <td>@{{ anotacion.created_at }}</td>
                            <td>@{{ anotacion.nombre_usuario  ?? 'NA'}}</td>
                            <td><span class="contenidotext" v-html="anotacion.anotacion"></span></td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <div><strong>Quiénes han leido el PQR: </strong></div>
                <table id="anotaciones" class="table table-bordered text-center mt-2">
                    <thead>
                        <tr class="custom-thead">
                            <td>Usuario</td>
                            <td>Rol</td>
                            <td>Accesos</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="leido in dataShow.pqr_leidos">
                            <td>@{{ leido.nombre_usuario ?? 'NA' }}</td>
                            <td>@{{ leido.tipo_usuario ?? 'NA'}}</td>
                            <td width="250">
                                <i class="fa fa-arrow-circle-down" aria-hidden="true" data-toggle="collapse" :data-target="'#learnMore'+leido.id"></i>
                                <div :id="'learnMore'+leido.id" class="collapse" v-html="leido.accesos"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <!-- Panel Flujo de producción documental -->
        <div id="show_cards" class="card" data-sortable-id="ui-general-1">
            <div class="card-body">


                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                    <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
                    <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_cards')" title="Ver en tabla" style="margin-left: auto;"><i id="btnTable" class="fa fa-th" style="color: #5f6368;"></i></button>
                    <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'p-q-r-s')" title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"></i> </button>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="collapse" id="collapseExample">
                    {{-- <div class="col-md-12">
            <button type="button" class="btn bg-success-lighter" @click="exportarHistorial('xlsx', dataShow.id, 'p-q-r-s')"><i class="fas fa-file-download"></i> Exportar Historial</button>
        </div> --}}
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <!-- Vertical Timeline -->
                                <section id="conference-timeline">

                                    <div class="timeline-start">
                                        <p>Inicio</p>
                                    </div>
                                    <div class="conference-center-line"></div>

                                    <div class="conference-timeline-content">
                                        <!-- Article -->

                                        <div class="timeline-article" v-for="(history, key) in dataShow.pqr_historial">

                                            <div style="cursor: pointer;" data-toggle="collapse" data-target="#historial_completo" v-bind:class="{
                                'content-left-container': key % 2 === 0,
                                'content-right-container': key % 2 !== 0
                            }">
                                                {{-- <span class="timeline-author"><b>Actualizado por:</b> @{{ history.users?.name ?? history.nombre_ciudadano }}</span> --}}

                                                <div v-bind:class="{
                                    'content-left': key % 2 === 0,
                                    'content-right': key % 2 !== 0
                                }">

                                                    <div style="display: flex; align-items: center;">
                                                        <!-- Contenedor de la imagen del perfil -->
                                                        <div style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%; margin-right: 10px;">
                                                            <!-- Imagen del perfil del usuario, si está disponible -->
                                                            <img v-if="history.users ? (history.users.url_img_profile != '' && history.users.url_img_profile !== 'users/avatar/default.png') : false" :src="'{{ asset('storage') }}/'+history.users.url_img_profile" alt="Imagen del perfil" style="width: 40px; height: 40px;">
                                                            <!-- Imagen predeterminada si no hay imagen de perfil o si la imagen predeterminada está configurada -->
                                                            <img v-else src="{{ asset('assets/img/user/profile.png') }}" alt="Imagen predeterminada del perfil" style="width: 40px; height: 40px;">
                                                        </div>
                                                        <!-- Nombre del autor -->
                                                        <span class="timeline-author">
                                                            <!-- Mostrar el nombre del usuario si está disponible, de lo contrario, mostrar el nombre del autor del historial o el nombre del ciudadano -->
                                                            @{{ history.users_name ? history.users_name : (history.users?.name ? history.users.name : history.nombre_ciudadano) }}
                                                        </span>
                                                    </div>
                                                    <hr>


                                                    <p>
                                                        <strong style="color:#00B0BD ">@{{ key + 1 }}. @{{ history.action }} </strong> <br>

                                                        {{-- <strong style="color:#00B0BD ">Estado: @{{ history.estado }}</strong> <br> --}}

                                                        <strong>Fecha y hora:</strong> @{{ history.date_format.day }} de @{{ history.date_format.monthcompleto }} de @{{ history.date_format.year }} @{{ history.date_format.hour }}<br>
                                                        <strong>Funcionario asignado:</strong> @{{ history.funcionario_destinatario ? history.funcionario_destinatario : 'N/A' }}<br>
                                                        <strong>Ciudadano:</strong> @{{ history.nombre_ciudadano ? history.nombre_ciudadano : 'N/A' }} - @{{ history.documento_ciudadano ? history.documento_ciudadano : 'N/A' }}<br>
                                                        <strong>Email:</strong> @{{ history.email_ciudadano ? history.email_ciudadano : 'N/A' }}<br>
                                                        <strong>Eje temático:</strong> @{{ history.nombre_ejetematico }}<br>
                                                        <strong>Fecha de vencimiento:</strong> @{{ history.fecha_vence }}<br>
                                                    <div class="row">
                                                        <strong class="col-md-2">Estado:</strong>
                                                        <strong class="text-center col-md-6" style="width: 102%; display: block;" :class="history.estado == 'Abierto' ? 'estado_abierto' : (history.estado == 'Cancelado' ? 'estado_cancelado' : (history.estado == 'Finalizado' && history.linea_tiempo == 'A tiempo' ? 'estado_finalizado_a_tiempo' : (history.estado == 'Finalizado vencido justificado' ? 'estado_finalizado_vencido_justificado' : (history.linea_tiempo == 'A tiempo' ? 'estado_a_tiempo' : (history.linea_tiempo == 'Próximo a vencer' ? 'estado_proximo_vencer' : 'estado_vencido')))))">
                                                            @{{ history.estado }} <br />
                                                            <strong v-if="history.linea_tiempo" :style="!history.leido ? 'font-weight: bold;' : ''">(@{{ history.linea_tiempo }})</strong>
                                                        </strong><br>

                                                    </div>


                                                    {{-- <span class="article-number">
                                        <strong>@{{ key + 1 }}</strong>
                                                    </span> --}}
                                                    </p>

                                                </div>



                                            </div>


                                            <div class="meta-date">
                                                <span class="date">@{{ history.date_format.day }}</span>
                                                <span class="month">@{{ history.date_format.month }}</span>
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
    </div>
</div>
<div class="row">
    <div class="col-12">

        <!-- Panel Historial -->
        <div id="show_table" class="card" title="Ver en lista" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="card-body">

                <div class="panel-heading ui-sortable-handle">
                    <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
                    <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_table')" style="margin-left: auto;"><i id="btnCard" class="fas fa-square" style="color: #5f6368;"></i></button>
                    <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'p-q-r-s')" title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"></i> </button>

                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="collapse" id="collapseExample">
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <table class="table table-striped fix-vertical-table">
                            <thead>
                                <tr class="font-weight-bold" style="background-color: #00B0BD; color: white; text-align: center;vertical-align: middle;">
                                    <td>Usuario</td>
                                    <td>Estado</td>
                                    <td>Eje temático</td>
                                    <td>Funcionario asignado</td>
                                    <td>Fecha de creación</td>
                                    <td>Fecha de vencimiento</td>
                                    <td>Última modificación</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="historial in dataShow.pqr_historial">
                                    <td>@{{ historial.users?.name ?? historial.nombre_ciudadano }}</td>
                                    <td>@{{ historial.estado }}</td>
                                    <td>@{{ historial.nombre_ejetematico }}</td>
                                    <td>@{{ historial.funcionario_destinatario }}</td>
                                    <td>@{{ historial.created_at }}</td>
                                    <td>@{{ historial.fecha_vence }}</td>
                                    <td>@{{ historial.updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</div>