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

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Nombre:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.nombre_ciudadano">@{{ dataShow.nombre_ciudadano}}.</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Categoria ciudadano:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.categoria_ciudadano">@{{ dataShow.categoria_ciudadano }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Correo:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.correo_ciudadano">@{{ dataShow.correo_ciudadano }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Documento:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.documento_ciudadano">@{{ dataShow.documento_ciudadano }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Teléfono:
                        </strong></label>
                    <label class="col-form-label col-md-8">No registra teléfono.</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Departamento:
                        </strong></label>
                    <label class="col-form-label col-md-8" >No registra teléfono.</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Ciudad:
                        </strong></label>
                    <label class="col-form-label col-md-8" >No registra ciudad.</label>
                </div>
            </div>



        </div>
    </div>
</div>

<!-- Panel Información ciudadano -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Destino</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Dependencia:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.dependencia_asignada">@{{ dataShow.dependencia_asignada}}.</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Funcionario:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.funcionario_asignado">@{{ dataShow.funcionario_asignado }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Listado funcionario con copia:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.funcionario_copia" v-html="dataShow.funcionario_copia"></label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Panel Información ciudadano -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Clasificación documental</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Oficina Productora:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.oficina_productora">@{{ dataShow.oficina_productora}}.</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Serie Sub-serie Documental:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.serie_subserie">@{{ dataShow.serie_subserie }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Panel Información PQR -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>PQRSDF</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>No. Radicado:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.id">@{{ dataShow.id }}.</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Folios:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.folios">@{{ dataShow.folios }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Anexos:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.anexos">@{{ dataShow.anexos }}.</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Canal:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.canal">@{{ dataShow.canal }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Tipo de solicitud:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.tipo">@{{ dataShow.tipo }}.</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Eje temático:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.nombre_ejetematico">@{{ dataShow.nombre_ejetematico }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Respuesta:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.respuesta" v-html="dataShow.respuesta"></label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Empresa a la que Traslada:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.empresa_traslado">@{{ dataShow.empresa_traslado }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Contenido:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.contenido" v-html="dataShow.contenido"></label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Correo integrado:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.id_correo_integrado">@{{ dataShow.id_correo_integrado }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Correspondencia recibida asociada:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.id_correspondence">@{{ dataShow.id_correspondence }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>


        </div>

    </div>
</div>

<!-- Panel Información ciudadano -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Devoluciones</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

                <!-- Panel Información ciudadano -->
        <div class="panel col-md-12" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h3 class="panel-title"><strong>Devolución interna</strong></h3>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Razón de devolución:
                                </strong></label>
                            <label class="col-form-label col-md-8" v-if="dataShow.oficina_productora">@{{ dataShow.devolucion}}.</label>
                            <label class="col-form-label col-md-8" v-else>No hay devolución interna</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Panel Información ciudadano -->
        <div class="panel col-md-12" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h3 class="panel-title"><strong>Devolución a ciudadano</strong></h3>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Pregunta a ciudadano:
                                </strong></label>
                            <label class="col-form-label col-md-8" v-if="dataShow.pregunta">@{{ dataShow.pregunta}}.</label>
                            <label class="col-form-label col-md-8" v-else>No hay devolución al ciudadano</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Respuesta del ciudadano:
                                </strong></label>
                            <label class="col-form-label col-md-8" v-if="dataShow.pregunta_respuesta">@{{ dataShow.pregunta_respuesta}}.</label>
                            <label class="col-form-label col-md-8" v-else>No hay devolución al ciudadano</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>

                <!-- Panel Información ciudadano -->
        <div class="panel col-md-12" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h3 class="panel-title"><strong>Cancelación</strong></h3>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Razón de cancelación:
                                </strong></label>
                            <label class="col-form-label col-md-8" v-if="dataShow.repuesta">@{{ dataShow.repuesta}}.</label>
                            <label class="col-form-label col-md-8" v-else>No existe cancelación</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panel Información ciudadano -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Gestión del trámite</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Descripción del trámite:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.desctramite">@{{ dataShow.desctramite}}.</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>


            <!-- Panel Información ciudadano -->
            <div class="panel col-md-12" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                    <h3 class="panel-title"><strong>información de Respuesta parcial</strong></h3>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Respuesta Parcial:
                                    </strong></label>
                                <label class="col-form-label col-md-8" v-if="dataShow.respuesta_parcial">@{{ dataShow.respuesta_parcial}}.</label>
                                <label class="col-form-label col-md-8" v-else>N/A</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Fecha de respuesta parcial:
                                    </strong></label>
                                <label class="col-form-label col-md-8" v-if="dataShow.fecha_fin_parcial">@{{ dataShow.fecha_fin_parcial}}.</label>
                                <label class="col-form-label col-md-8" v-else>N/A</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>No. De oficio de solicitud:
                                    </strong></label>
                                <label class="col-form-label col-md-8" v-if="dataShow.no_oficio_solicitud">@{{ dataShow.no_oficio_solicitud}}.</label>
                                <label class="col-form-label col-md-8" v-else>N/A</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Adjunto oficio solicitud:
                                    </strong></label>
                                <a :href="dataShow.adj_oficio_solicitud" Target="_blank" class="col-form-label col-md-8" v-if="dataShow.adj_oficio_solicitud">Ver adjunto</a>
                                <label class="col-form-label col-md-8" v-else>N/A</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Estado Field -->
                        <label class="col-form-label col-md-2 text-black-transparent-7"><strong>No. De oficio de respuesta:
                                    </strong></label>
                        <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.id_correspondence ?? 'NA'}}.</dd>
                    </div>

                    <div class="row">
                        <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Adjunto oficio respuesta:
                                    </strong></label>
                        <dd class="col-12" v-if="dataShow.adjunto_correspondence && dataShow.adjunto_correspondence.length > 0">
                            <viewer-attachement v-if="dataShow.adjunto_correspondence" :list="dataShow.adjunto_correspondence"></viewer-attachement>
                        </dd>
                        <dd v-else class="col-sm-9 col-md-9 col-lg-9">
                            <span>No tiene adjunto</span>
                        </dd>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Panel Información ciudadano -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Finalizado justificado</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Observación:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.fin_just_observacion">@{{ dataShow.fin_just_observacion}}.</label>
                    <label class="col-form-label col-md-8" v-else>El PQRSDF no tiene observación por vencido justificado.</label>
                </div>
            </div>
        </div>
    </div>
</div>
