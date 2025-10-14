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
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.ciudadano_users ? dataShow.ciudadano_users.name : dataShow.nombre_ciudadano}}.</dd>

            <!-- Email Ciudadano Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Correo'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.ciudadano_users ? dataShow.ciudadano_users.email : dataShow.email_ciudadano}}.</dd>
        </div>

        <div class="row">
            <!-- Ciudadano Users Id Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">N° Documento:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.documento_ciudadano }}.</dd>
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
            <!-- Pqr ID Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('No. Radicado'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.pqr_id }}.</dd>

            <!-- Folios Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Folios'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.folios ?? 'NA' }}.</dd>
        </div>


        <div class="row">
            <!-- Anexos Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Anexos'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.anexos ?? 'NA'}}.</dd>

            <!-- Canal Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Canal'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.canal ?? 'NA' }}.</dd>
        </div>

        <div class="row">
            <!-- Pqr Tipo Solicitud Id Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Tipo de solicitud'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.pqr_tipo_solicitud?.nombre }}.</dd>

            <!-- Pqr Eje Tematico Id Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Eje temático'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 " v-if="dataShow.pqr_eje_tematico_id">@{{ dataShow.nombre_ejetematico ?? 'NA' }}.</dd>
        </div>

        <div class="row">
            <!-- Estado Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Estado'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.estado  ?? 'NA'}}.</dd>

            <!-- Contenido Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3">@lang('Contenido'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.contenido ?? 'NA' }}.</dd>
        </div>

        <div class="row">
            <!-- Document pdf Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Documento principal del PQR'):</dt>
            <dd class="col-12" v-if="dataShow.document_pdf && dataShow.document_pdf.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.document_pdf" :list="dataShow.document_pdf"></viewer-attachement>
            </dd>
            <dd class="col-12" v-else>
                <span>No tiene adjuntos</span>
            </dd>

            <!-- Adjunto ciudadano Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Anexos del ciudadano'):</dt>
            <dd class="col-12" v-if="dataShow.adjunto_ciudadano && dataShow.adjunto_ciudadano.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adjunto_ciudadano" :list="dataShow.adjunto_ciudadano"></viewer-attachement>
            </dd>
            <dd class="col-12" v-else>
                <span>No tiene adjuntos</span>
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

        <span v-if="dataShow.pqr_correspondence">
            <hr />
            <div class="row">
                <!-- Correspondence Received Id Field -->
                <dt class="col-sm-3 col-md-3 col-lg-3">Correspondencia recibida asociada:</dt>
                <a class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.pqr_correspondence.consecutive }}.</a>
            </div>

            <div class="row mt-2" v-if="dataShow.pqr_correspondence?.document_pdf">
                <br>
                <!-- Adjuntos Correspondence Received Id Field -->
                <dt class="col-sm-3 col-md-3 col-lg-3">Documentos principal de la correspondencia recibida asociada:</dt>
                <dd class="col-12" v-if="dataShow.pqr_correspondence.document_pdf && dataShow.pqr_correspondence.document_pdf.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.pqr_correspondence.document_pdf" :list="dataShow.pqr_correspondence.document_pdf"></viewer-attachement>
                </dd>
                <dd class="col-12" v-else>
                    <span>No tiene documento principal</span>
                </dd>
            </div>

            <div class="row mt-2" v-if="dataShow.pqr_correspondence?.attached_document">
                <br>
                <!-- Adjuntos Correspondence Received Id Field -->
                <dt class="col-sm-3 col-md-3 col-lg-3">Anexos correspondencia recibida asociada:</dt>
                <dd class="col-12" v-if="dataShow.pqr_correspondence.attached_document && dataShow.pqr_correspondence.attached_document.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.pqr_correspondence.attached_document" :list="dataShow.pqr_correspondence.attached_document"></viewer-attachement>
                </dd>
                <dd class="col-12" v-else>
                    <span>No tiene anexos</span>
                </dd>
            </div>
        </span>
    </div>
</div>

<!-- Panel Devoluciones y cancelaciones -->
<div v-if="dataShow.devolucion || dataShow.estado == 'Cancelado'  || dataShow.pregunta_ciudadano " class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Devoluciones y cancelación</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Panel Devolución interna -->
        <div  v-if="dataShow.devolucion" class="panel col-md-12" data-sortable-id="ui-general-1">
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
                    <dd class="col-sm-9 col-md-9 col-lg-9 " v-if="dataShow.devolucion">@{{ dataShow.devolucion ?? 'NA' }}.</dd>
                    <dd class="col-sm-9 col-md-9 col-lg-9 " v-else>No hay devolución interna.</dd>
                </div>
            </div>
        </div>
        <!-- Panel Devolución a ciudadano -->
        <div v-if="dataShow.pregunta_ciudadano" class="panel col-md-12" data-sortable-id="ui-general-1">
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
        <div v-if="dataShow.estado == 'Cancelado'" class="panel col-md-12" data-sortable-id="ui-general-1">
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
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.tipo_finalizacion  ?? 'NA'}}.</dd>

            <!-- Empresa traslado Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 " v-if="dataShow.tipo_finalizacion == 'PQRS para trasladar a otra entidad'">@lang('Empresa a la que traslada'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 " v-if="dataShow.tipo_finalizacion == 'PQRS para trasladar a otra entidad'">@{{ dataShow.tipo_finalizacion  ?? 'NA'}}.</dd>
        </div>

        <div class="row">
            <!-- Descripción trámite Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Descripción del trámite'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.descripcion_tramite ?? 'NA'}}.</dd>
        </div>

        <hr />
            <div class="row">
                <!-- Respuesta Parcial Field -->
                <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Información de respuesta parcial'):</dt>
                <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.respuesta_parcial">@{{ dataShow.respuesta_parcial  ?? 'NA'}}.</dd>
                <dt class=" col-sm-3 col-md-3 col-lg-3 " v-if="dataShow.respuesta_parcial">@lang('Fecha de respuesta parcial'):</dt>
                <dd class="col-sm-9 col-md-9 col-lg-9 " v-if="dataShow.respuesta_parcial">@{{ dataShow.fecha_fin }}.</dd>
                <dd class="col-sm-9 col-md-9 col-lg-9 " v-else>N/A.</dd>
            </div>
        <hr />

        <div class="row">
            <!-- Respuesta Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Respuesta'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 alert alert-success w-100">@{{ dataShow.respuesta  ?? 'NA'}}.</dd>
        </div>

        <div v-if="dataShow.no_oficio_respuesta" class="row">
            <!-- No Oficio Respuesta Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('No. De oficio de respuesta'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.no_oficio_respuesta  ?? 'NA'}}.</dd>

            <!-- Adj Oficio Respuesta Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Adjunto oficio de respuesta'):</dt>
            <dd class="col-12" v-if="dataShow.adj_oficio_respuesta && dataShow.adj_oficio_respuesta.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adj_oficio_respuesta" :list="dataShow.adj_oficio_respuesta "></viewer-attachement>
            </dd>
            <dd class="col-12" v-else>
                <span>No tiene adjuntos</span>
            </dd>
        </div>

        <div v-if="dataShow.no_oficio_solicitud" class="row">
            <!-- No Oficio Solicitud Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('N. De oficio de solicitud'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.no_oficio_solicitud  ?? 'NA'}}.</dd>

            <!-- Adj Oficio Solicitud Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Adjunto oficio solicitud'):</dt>
            <dd class="col-12" v-if="dataShow.adj_oficio_solicitud && dataShow.adj_oficio_solicitud.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adj_oficio_solicitud" :list="dataShow.adj_oficio_solicitud"></viewer-attachement>
            </dd>
            <dd class="col-12" v-else>
                <span>No tiene adjuntos</span>
            </dd>
        </div>
        <hr />
        <div v-if="dataShow.adjunto" class="row">
            <!-- Adjunto Field -->
            <dt class=" col-sm-3 col-md-3 col-lg-3 ">@lang('Archivos adjuntos'):</dt>
            <dd class="col-12" v-if="dataShow.adjunto && dataShow.adjunto.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adjunto" :list="dataShow.adjunto"></viewer-attachement>
            </dd>
            <dd class="col-12" v-else>
                <span>No tiene adjuntos</span>
            </dd>
        </div>
    </div>
</div>

<!-- Panel Seguimiento al trámite -->
<div v-if="false" class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Seguimiento al trámite</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div><strong>Anotaciones: </strong></div>
        <table id="anotaciones" class="table text-center mt-2" border="1">
            <thead>
               <tr class="font-weight-bold" style="background-color: #00bcd47d">
                  <td>Fecha</td>
                  <td>Usuario</td>
                  <td>Anotación</td>
               </tr>
            </thead>
            <tbody>
               <tr v-for="anotacion in dataShow.pqr_anotacions">
                  <td>@{{ anotacion.created_at  ?? 'NA'}}</td>
                  <td>@{{ anotacion.nombre_usuario  ?? 'NA'}}</td>
                  <td>@{{ anotacion.anotacion  ?? 'NA'}}</td>
               </tr>
            </tbody>
        </table>
        <br />
        <div><strong>Quiénes han leido el PQR: </strong></div>
        <table id="anotaciones" class="table text-center mt-2" border="1">
            <thead>
               <tr class="font-weight-bold" style="background-color: #ff98008a">
                  <td>Usuario</td>
                  <td>Rol</td>
                  <td>Accesos</td>
               </tr>
            </thead>
            <tbody>
               <tr v-for="leido in dataShow.pqr_leidos">
                  <td>@{{ leido.nombre_usuario  ?? 'NA'}}</td>
                  <td>@{{ leido.tipo_usuario ?? 'NA' }}</td>
                  <td width="250">
                    <i class="fa fa-arrow-circle-down" aria-hidden="true" data-toggle="collapse" :data-target="'#learnMore'+leido.id"></i>
                    <div :id="'learnMore'+leido.id" class="collapse" v-html="leido.accesos"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

</div>

<!-- Panel Historial -->
<div v-if="false" class="panel col-md-12" data-sortable-id="ui-general-1">
<!-- begin panel-heading -->
<div class="panel-heading ui-sortable-handle">
    <h3 class="panel-title"><strong>Historial de cambios</strong></h3>
</div>
<!-- end panel-heading -->
<!-- begin panel-body -->
<div class="panel-body">
    <div class="table-responsive">
        <table id="historial" class="table text-center mt-2" border="1">
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
                <td>@{{ historial.estado ?? 'NA'}}</td>
                <td>@{{ historial.nombre_ejetematico ?? 'NA'}}</td>
                <td>@{{ historial.funcionario_destinatario ?? 'NA'}}</td>
                <td>@{{ historial.created_at ?? 'NA'}}</td>
                <td>@{{ historial.fecha_vence ?? 'NA'}}</td>
                <td>@{{ historial.updated_at ?? 'NA'}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
