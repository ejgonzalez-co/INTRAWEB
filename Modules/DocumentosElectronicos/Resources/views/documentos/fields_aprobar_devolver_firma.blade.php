<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Acción sobre el documento</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="col-md-12">
            <div class="form-group row m-b-15">
                <label for="accion_documento" class="col-form-label col-md-4 required">¿Qué desea hacer con el documento?:</label>
                <div class="col-md-8">
                    <select class="form-control" v-model="dataForm.accion_documento" name="accion_documento" id="accion_documento" required>
                        <option value="">Seleccione</option>
                        <option value="Aprobar Firma">Aprobar Firma</option>
                        <option value="Devolver para modificaciones">Devolver para modificaciones</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group row m-b-15">
                <label for="danger" class="col-form-label col-md-4 required">Observaciones:</label>
                <div class="col-md-8">
                    <textarea class="form-control" required type="text" v-model="dataForm.observaciones" placeholder=""></textarea>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Versiones de firma conjunta</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover fix-vertical-table">
                        <thead>
                            <tr>
                                <th>Número de versión</th>
                                <th>Creado por</th>
                                <th>Documento</th>
                                <th>@lang('Created_at')</th>
                                <th>@lang('State')</th>
                                <th>@lang('Observation')</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if='dataForm.de_documento_versions'>
                                <td>@{{ dataForm.de_documento_versions[0].numero_version }}</td>
                                <td>@{{ dataForm.de_documento_versions[0].nombre_usuario }}</td>
                                <td style="display: block;">
                                    <a class="col-9 text-truncate"
                                        @click="$parent.getPathDocument(dataForm.de_documento_versions[0].document_pdf_temp, true);"
                                        href="javascript:;">Ver documento</a>
                                    <div v-if="$parent.routeLoading" class="spinner" style="position: sticky; float: right; right: 0; width: 15px; height: 15px; margin-bottom: 3px;"></div>
                                </td>
                                <td>@{{ dataForm.de_documento_versions[0].created_at }}</td>
                                <td>@{{ dataForm.de_documento_versions[0].estado }}</td>
                                <td>@{{ dataForm.de_documento_versions[0].observacion }}</td>
                            </tr>
                            <tr>
                                <td colspan="6"><strong>Funcionarios para firma versión @{{ dataForm.de_documento_versions[0].numero_version }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Funcionario</th>
                                                <th>Tipo de usuario</th>
                                                <th>Estado</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>
                                        <body>
                                            <tr v-for="(sign, key) in dataForm.de_documento_versions[0].de_documento_firmars">
                                                <td>@{{ sign.created_at }}</td>
                                                <td>@{{ sign.nombre_usuario }}</td>
                                                <td>@{{ sign.tipo_usuario }}</td>
                                                <td>@{{ sign.estado }}</td>
                                                <td>@{{ sign.observacion }}</td>
                                            </tr>
                                        </body>
                                    </table>
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
