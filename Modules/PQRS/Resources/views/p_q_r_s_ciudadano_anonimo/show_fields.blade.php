<!-- Panel Información ciudadano -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Ciudadano</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">
            <!-- Ciudadano Users Id Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Nombre'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.ciudadano_users ? dataShow.ciudadano_users.name : dataShow.nombre_ciudadano}}.</dd>

            <!-- Email Ciudadano Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Correo'):</dt>
            <dd v-if="dataShow.documento_ciudadano =='Anónimo'" class="col-sm-3 col-md-3 col-lg-3">NA.</dd>
            <dd v-else class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.ciudadano_users ? dataShow.ciudadano_users.email : dataShow.email_ciudadano}}.</dd>
        </div>

        <div class="row">
            <!-- Ciudadano Users Id Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">N° Documento:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.documento_ciudadano ?? 'NA'}}.</dd>
        </div>
    </div>
</div>

<!-- Panel Información PQR -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Detalle del PQR</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">
            <!-- Campo de fecha de recepcion -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">Fecha de recepción:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.created_at }}.</dd>

            {{-- Campo de fecha de modificacion --}}
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">Fecha de modificación:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.updated_at }}.</dd>
        </div>

        <div class="row">
            <!-- Pqr ID Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('No. Radicado'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.pqr_id ?? 'NA'}}.</dd>

            <!-- Folios Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Folios'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.folios ?? 'NA'}}.</dd>
        </div>


        <div class="row">
            <!-- Anexos Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Anexos'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.anexos?? 'NA' }}.</dd>

            <!-- Canal Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Canal'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.canal ?? 'NA' }}.</dd>
        </div>

        <div class="row">
            <!-- Pqr Tipo Solicitud Id Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Tipo de solicitud'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.pqr_tipo_solicitud?.nombre   ?? 'NA' }}.</dd>

            <!-- Estado Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Estado'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.estado ?? 'NA'}}.</dd>
        </div>

        <div class="row">
            <!-- Contenido Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3">@lang('Contenido'):</dt>
            <dd style="text-align: justify" class="col-sm-9 col-md-9 col-lg-9" v-html="dataShow.contenido"></dd>
        </div>

        <div class="row">
            <!-- Numero matricula Field -->
            <dt class="col-sm-3 col-md-3 col-lg-3 ">@lang('Número de matrícula'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.no_matricula ?? 'NA' }}.</dd>

            <!-- Direccion predio Field -->
            <dt class="col-sm-3 col-md-3 col-lg-3 ">@lang('Dirección del predio'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.direccion_predio ?? 'NA'}}.</dd>
        </div>

        <div class="row">
            <!-- Motivos hechos Field -->
            <dt class="col-sm-3 col-md-3 col-lg-3 ">@lang('Motivos o hechos'):</dt>
            <dd style="text-align: justify" class="col-sm-9 col-md-9 col-lg-9" v-html="dataShow.motivos_hechos"></dd>
        </div>

        <div class="row">
            <!-- Document pdf Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Documento principal del PQR'):</dt>
            <dd class="col-12" v-if="dataShow.document_pdf && dataShow.document_pdf.length > 0">

                <viewer-attachement :link-file-name="true" v-if="dataShow.document_pdf" :list="dataShow.document_pdf "></viewer-attachement>

            </dd>
            <dd class="col-12"  v-else>
                <span>No tiene adjunto</span>
            </dd>

        </div>

        <div class="row" v-if="!dataShow.adjunto_finalizado_visible">
            <!-- Document pdf Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">Documento PQR finalizada:</dt>
            <dd class="col-12" v-if="dataShow.adjunto_finalizado && dataShow.adjunto_finalizado.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adjunto_finalizado" :list="dataShow.adjunto_finalizado"></viewer-attachement>
            </dd>
        </div>

        <div class="row">
            <!-- Document pdf Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">Anexos del ciudadano:</dt>
            <dd class="col-12" v-if="dataShow.adjunto_ciudadano && dataShow.adjunto_ciudadano.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adjunto_ciudadano" :list="dataShow.adjunto_ciudadano"></viewer-attachement>
            </dd>
            <dd class="col-12"  v-else>
                <span>No tiene adjunto</span>
            </dd>

        </div>

        <div class="row" style="margin-top: 15px">
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
                    <span v-if="dataShow.fecha_vence"><strong>Tiempo restante hasta vencimiento: </strong>@{{ dataShow.dias_restantes ? dataShow.dias_restantes : dataShow.dias_restantes_pqr }}</span>
                </span>
            </dd>
            </div>

        </div>

        <hr />
            <div class="row">
                <!-- Respuesta Parcial Field -->
                <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Información de respuesta parcial'):</dt>
                <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.respuesta_parcial">@{{ dataShow.respuesta_parcial ?? 'NA' }}.</dd>
                <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 " v-if="dataShow.respuesta_parcial">@lang('Fecha de respuesta parcial'):</dt>
                <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.fecha_fin_parcial">@{{ dataShow.fecha_fin_parcial }}.</dd>
                <dd class="col-sm-9 col-md-9 col-lg-9 " v-else>N/A.</dd>

                <!-- adjunto_r_parcial Field -->
                <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Adjunto respuesta parcial:</dt>
                <dd class="col-12" v-if="dataShow.adjunto_r_parcial && dataShow.adjunto_r_parcial.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.adjunto_r_parcial" :list="dataShow.adjunto_r_parcial"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjuntos</span>
                </dd>
            </div>
        <hr />

        <span v-if="dataShow.pqr_correspondence">
            <hr />
            <div class="row">
                <!-- Correspondence Received Id Field -->
                <dt class=" col-sm-3 col-md-3 col-lg-3">Correspondencia recibida asociada:</dt>
                <a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ url('/') }}/correspondence/external-receiveds?qder='+dataShow.recibida_encrypted_id.id_correspondence_recibida">@{{ dataShow.pqr_correspondence.consecutive }}.</a>
            </div>


            <div class="row">
                <!-- Document pdf Field -->
                <dt class=" col-sm-3 col-md-3 col-lg-3 ">Documento principal de la correspondencia:</dt>
                <dd class="col-12" v-if="dataShow.pqr_correspondence.document_pdf && dataShow.pqr_correspondence.document_pdf.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.pqr_correspondence.document_pdf" :list="dataShow.pqr_correspondence.document_pdf"></viewer-attachement>
                </dd>
                <dd class="col-12"  v-else>
                    <span>No tiene adjunto</span>
                </dd>

            </div>

            <div class="row">
                <!-- Document pdf Field -->
                <dt class=" col-sm-3 col-md-3 col-lg-3 ">Anexos de la correspondencia:</dt>
                <dd class="col-12" v-if="dataShow.pqr_correspondence.attached_document && dataShow.pqr_correspondence.attached_document.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.pqr_correspondence.attached_document" :list="dataShow.pqr_correspondence.attached_document"></viewer-attachement>
                </dd>
                <dd class="col-12"  v-else>
                    <span>No tiene adjunto</span>
                </dd>

            </div>
        </span>

        <!-- Panel Gestión del trámite -->
<div v-if="dataShow.tipo_finalizacion" class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Gestión del trámite</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row" v-if="dataShow.estado == 'Finalizado'">
            <!-- Tipo Finalizacion Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Tipo de finalizacion'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.tipo_finalizacion ?? 'NA'}}.</dd>

            <!-- Empresa traslado Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 " v-if="dataShow.tipo_finalizacion == 'PQRS para trasladar a otra entidad'">@lang('Empresa a la que traslada'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3" v-if="dataShow.tipo_finalizacion == 'PQRS para trasladar a otra entidad'">@{{ dataShow.empresa_traslado  ?? 'NA' }}.</dd>
        </div>

        <div class="row">
            <!-- Descripción trámite Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Descripción del trámite'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9  alert alert-success w-100" role="alert">@{{ dataShow.descripcion_tramite ?? 'NA'}}.</dd>
        </div>

        <hr />
            <div class="row">
                <!-- Respuesta Parcial Field -->
                <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Información de respuesta parcial'):</dt>
                <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.respuesta_parcial">@{{ dataShow.respuesta_parcial ?? 'NA' }}.</dd>
                <dt class=" col-sm-3 col-md-3 col-lg-3 " v-if="dataShow.respuesta_parcial">@lang('Fecha de respuesta parcial'):</dt>
                <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.fecha_fin">@{{ dataShow.fecha_fin }}.</dd>
                <dd class="col-sm-9 col-md-9 col-lg-9 " v-else>N/A.</dd>

                <!-- adjunto_r_parcial Field -->
                <dt class=" col-sm-3 col-md-3 col-lg-3 ">Adjunto respuesta parcial:</dt>
                <dd class="col-12" v-if="dataShow.adjunto_r_parcial && dataShow.adjunto_r_parcial.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.adjunto_r_parcial" :list="dataShow.adjunto_r_parcial"></viewer-attachement>
                </dd>
                <dd class="col-12"  v-else>
                    <span>No tiene adjuntos</span>
                </dd>
            </div>
        <hr />

        <div class="row" v-if="dataShow.pqr_correspondence_external">
            <!-- No Oficio Respuesta Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">Correspondencia enviada asociada:</dt>
            <a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ url('/') }}/correspondence/externals?qder='+dataShow.external_encrypted_id.id_correspondence_external">@{{ dataShow.pqr_correspondence_external.consecutive }}.</a>
            <br>
            <!-- Adj Oficio Respuesta Field -->
            <dt class=" col-4 ">Documento principal de la correspondencia enviada:</dt>
            <dd class="col-8" v-if="dataShow.pqr_correspondence_external.document_pdf && dataShow.pqr_correspondence_external.document_pdf.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.pqr_correspondence_external.document_pdf" :list="dataShow.pqr_correspondence_external.document_pdf"></viewer-attachement>
            </dd>
            <dd class="col-12"  v-else>
                <span>No tiene adjunto</span>
            </dd>
        </div>
        <br>

        <div class="row">
            <!-- Respuesta Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Respuesta'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 alert-success w-100">@{{ dataShow.respuesta ?? 'NA' }}.</dd>
        </div>

        <div class="row">
            <!-- No Oficio Respuesta Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('No. De oficio de respuesta'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.no_oficio_respuesta ?? 'NA' }}.</dd>

            <!-- Adj Oficio Respuesta Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Adjunto oficio de respuesta'):</dt>
            <dd class="col-12" v-if="dataShow.adj_oficio_respuesta && dataShow.adj_oficio_respuesta.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adj_oficio_respuesta" :list="dataShow.adj_oficio_respuesta"></viewer-attachement>
            </dd>
            <dd class="col-12"  v-else>
                <span>No tiene adjuntos</span>
            </dd>
        </div>

        <div class="row">
            <!-- No Oficio Solicitud Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('N. De oficio de solicitud'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.no_oficio_solicitud  ?? 'NA'}}.</dd>

            <!-- Adj Oficio Solicitud Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Adjunto oficio solicitud'):</dt>
            <dd class="col-12" v-if="dataShow.adj_oficio_solicitud && dataShow.adj_oficio_solicitud.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adj_oficio_solicitud" :list="dataShow.adj_oficio_solicitud"></viewer-attachement>
            </dd>
            <dd class="col-12"  v-else>
                <span>No tiene adjuntos</span>
            </dd>
        </div>
        <hr />
        <div class="row">
            <!-- Adjunto Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Archivos adjuntos'):</dt>
            <dd class="col-12" v-if="dataShow.adjunto && dataShow.adjunto.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adjunto" :list="dataShow.adjunto"></viewer-attachement>
            </dd>
            <dd class="col-12"  v-else>
                <span>No tiene adjuntos</span>
            </dd>
        </div>
    </div>
</div>
    </div>
</div>

<!-- Panel Devoluciones y cancelaciones -->
<div v-if="dataShow.estado == 'Cancelado'" class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Devoluciones y cancelación</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Panel Devolución interna -->
        <div class="panel col-md-12" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h3 class="panel-title"><strong>Devolución interna</strong></h3>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <!-- Devolucion Field -->
                    <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Razón de la devolucion'):</dt>
                    <dd class="col-sm-9 col-md-9 col-lg-9 " v-if="dataShow.devolucion">@{{ dataShow.devolucion ?? 'NA'}}.</dd>
                    <dd class="col-sm-9 col-md-9 col-lg-9 " v-else>No hay devolución interna.</dd>
                </div>
            </div>
        </div>
        <!-- Panel Devolución a ciudadano -->
        <div class="panel col-md-12" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h3 class="panel-title"><strong>Devolución a ciudadano</strong></h3>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <!-- Pregunta Ciudadano Field -->
                    <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Pregunta al ciudadano'):</dt>
                    <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.pregunta_ciudadano">@{{ dataShow.pregunta_ciudadano ?? 'NA' }}.</dd>
                    <dd class="col-sm-9 col-md-9 col-lg-9 " v-else>No hay devolución al ciudadano.</dd>
                </div>

                <div class="row">
                    <!-- Respuesta Ciudadano Field -->
                    <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Respuesta del ciudadano'):</dt>
                    <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.respuesta_ciudadano">@{{ dataShow.respuesta_ciudadano  ?? 'NA'}}.</dd>
                    <dd class="col-sm-9 col-md-9 col-lg-9 " v-else>No hay devolución al ciudadano.</dd>
                </div>
            </div>
        </div>
        <!-- Panel Cancelación -->
        <div class="panel col-md-12" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h3 class="panel-title"><strong>Cancelación</strong></h3>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <!-- Cancelación Field -->
                    <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Razón de la cancelación'):</dt>
                    <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.estado == 'Cancelado'">@{{ dataShow.respuesta  ?? 'NA'}}.</dd>
                    <dd class="col-sm-9 col-md-9 col-lg-9 " v-else>No existe cancelación.</dd>
                </div>
            </div>
        </div>
    </div>
</div>
