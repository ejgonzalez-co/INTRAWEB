<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Calidad Macroproceso Id Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Macroproceso'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.macro_proceso?.nombre }}.</dd>
        </div>

        <div class="row">
            <!-- Nombre Solicitante Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Nombre del solicitante'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.nombre_solicitante }}.</dd>

            <!-- Cargo Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Cargo'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.cargo }}.</dd>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información de la solicitud</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Tipo Solicitud Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Tipo de solicitud'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.tipo_solicitud }}.</dd>

            <!-- Tipo Documento Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Tipo de documento'):</dt>
            <dd class="col-3">@{{ dataShow.documento_tipo_documento?.nombre }}.</dd>
        </div>

        <div class="row">
            <!-- Codigo Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Código'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.codigo }}.</dd>

            <!-- Nombre Documento Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Nombre del documento'):</dt>
            <dd class="col-3">@{{ dataShow.nombre_documento }}.</dd>
        </div>

        <div class="row">
            <!-- Version Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Versión'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.version }}.</dd>

            <!-- Calidad Proceso Id Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Proceso'):</dt>
            <dd class="col-3">@{{ dataShow.proceso?.nombre }}.</dd>
        </div>

        <div class="row">
            <!-- Estado Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Estado'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.estado }}.</dd>

            <!-- Justificacion Solicitud Field -->
            <dt class="text-inverse col-3">@lang('Justificacion de la solicitud'):</dt>
            <dd class="col-3">@{{ dataShow.justificacion_solicitud }}.</dd>
        </div>

        <div class="row">
            <!-- Adjunto Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Adjunto'):</dt>
            <dd class="col-9 text-truncate">
                <viewer-attachement :list="dataShow.adjunto" :key="dataShow.adjunto"></viewer-attachement>
            </dd>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Revisión metodológica y técnica</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Funcionario Responsable Field -->
            <dt class="text-inverse col-3">@lang('Funcionario responsable de atender la solicitud'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.funcionario_responsable }}.</dd>

            <!-- Cargo Responsable Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Cargo de responsable'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.cargo_responsable }}.</dd>
        </div>

        <div class="row" v-if="dataShow.observaciones">
            <!-- Observaciones Field -->
            <dt class="text-inverse col-3 text-truncate">@lang('Observaciones'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.observaciones }}.</dd>
        </div>
    </div>
</div>

<!-- Panel Historial de cambios -->
<div id="show_table" class="panel" title="Ver en lista" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <button class="btn btn-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="fa fa-hand-pointer m-r-2"></i>Ver historial de cambios</button>
        <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'solicitud-documental')" title="Descargar flujo documental" style="margin-left: auto;"><i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"></i></button>
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
                                <tr class="font-weight-bold"  style="background-color: #00B0BD; color: white; text-align: center;vertical-align: middle;" >
                                    <td style="width: 12%;">Tipo de solicitud</th>
                                    <td style="width: 8%;">Proceso</th>
                                    <td style="width: 10%;">Código</th>
                                    <td style="width: 12%;">Tipo de documento</th>
                                    <td style="width: 16%;">Nombre del documento</th>
                                    <td style="width: 14%;">Estado de la solicitud</th>
                                    <td style="width: 12%;">Fecha de creación</th>
                                    <td style="width: 14%;">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(history, key) in dataShow.documento_solicitud_documental_historials">
                                    <td>@{{ history.tipo_solicitud }}</td>
                                    <td>@{{ history.proceso?.nombre ?? 'N/A' }}</td>
                                    <td>@{{ history.codigo }}</td>
                                    <td>@{{ history.documento_tipo_documento?.nombre ?? "N/A" }}</td>
                                    <td>@{{ history.nombre_documento }}</td>
                                    <td>@{{ history.estado }}</td>
                                    <td>@{{ history.created_at }}</td>
                                    <td>@{{ history.observaciones }}</td>
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
