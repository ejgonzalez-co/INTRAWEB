<!-- Panel Comunicación-->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Detalles de la comunicación</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Consecutivo Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Consecutivo'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.consecutivo }}.</dd>
        </div>

        <div class="row">
            <!-- Correo Remitente Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Correo del remitente'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.correo_remitente }}.</dd>
        </div>

        <div class="row">
            <!-- Nombre Remitente Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Nombre del remitente'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.nombre_remitente }}.</dd>
        </div>

        <div class="row">
            <!-- Fecha Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Fecha'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.fecha }}.</dd>
        </div>

        <div class="row">
            <!-- Estado Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Estado'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.estado }}.</dd>
        </div>

        <div class="row">
            <!-- Clasificacion Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Clasificación'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.clasificacion }}.</dd>
        </div>

        <span v-if="dataShow.external_received">
            <hr />
            <div class="row">
                <!-- Correspondence Received Id Field -->
                <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">Correspondencia recibida asociada:</dt>
                <a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ url('/') }}/correspondence/external-receiveds?qder='+dataShow.recibida_encrypted_id.id_correspondence_recibida">@{{ dataShow.external_received.consecutive }}.</a>
            </div>

            
            <div class="row">
                <!-- Document pdf Field -->
                <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Documento principal de la correspondencia:</dt>
                <dd class="col-12" v-if="dataShow.external_received.document_pdf && dataShow.external_received.document_pdf.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.external_received.document_pdf" :list="dataShow.external_received.document_pdf"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjunto</span>
                </dd>

            </div>

            <div class="row">
                <!-- Document pdf Field -->
                <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Anexos de la correspondencia:</dt>
                <dd class="col-12" v-if="dataShow.external_received.attached_document && dataShow.external_received.attached_document.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.external_received.attached_document" :list="dataShow.external_received.attached_document"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjunto</span>
                </dd>

            </div>
        </span>
    </div>
</div>

<!-- Panel Contenido de la comunicación-->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Contenido de la comunicación</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">
            <!-- Asunto Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Asunto'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9">
                <span v-if="dataShow.asunto">@{{ dataShow.asunto }}.</span>
                <span v-else>(sin asunto)</span>
            </dd>
        </div>

        <div class="row">
            <!-- Contenido Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Contenido'):</dt>
            <dd class="col-12 mt-3 pl-3" v-html="dataShow.contenido"></dd>
        </div>

    </div>
</div>

<!-- Panel Adjuntos-->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.adjuntos_correo?.length > 0">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Adjuntos</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <ul>
                        <li v-for="(adjunto, key) in dataShow.adjuntos_correo">
                            <viewer-attachement :link-file-name="true" v-if="adjunto.adjunto" :list="adjunto.adjunto"></viewer-attachement>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panel Historial -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
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
                <tr class="font-weight-bold">
                    <td>Fecha de creación</td>
                    <td>Consecutivo</td>
                    <td>Asunto</td>
                    <td>Correo del remitente</td>
                    <td>Fecha</td>
                    <td>Estado</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="historial in dataShow.correo_integrado_historial">
                    <td>@{{ historial.created_at }}</td>
                    <td>@{{ historial.consecutivo }}</td>
                    <td>@{{ historial.asunto }}</td>
                    <td>@{{ historial.correo_remitente }}</td>
                    <td>@{{ historial.fecha }}</td>
                    <td>@{{ historial.estado }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Panel Correspondencia recibida -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.external_received">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Datos de la correspondencia recibida asociada</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Consecutivo Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Consecutivo'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.external_received.consecutive }}.</dd>
        </div>

        <div class="row">
            <!-- Asunto Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Asunto'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.external_received.issue }}.</dd>
        </div>

        <div class="row">
            <!-- Folios Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Folios'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.external_received.folio }}.</dd>
        </div>

        <div class="row">
            <!-- Anexos Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Anexos'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.external_received.annexed }}.</dd>
        </div>

        <div class="row">
            <!-- Tipo de documento Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Tipo de documento'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.external_received.type_documentary_name }}.</dd>
        </div>

        <div class="row">
            <!-- Canal Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Canal'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.external_received.channel_name }}.</dd>
        </div>
        
        <div class="row">
            <!-- Novedad Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Novedad'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.external_received.novelty }}.</dd>
        </div>
    </div>
</div>