<!-- Panel -->
<!-- Panel datos origen-->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos de origen</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Ciudadano:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.nombre_ciudadano">@{{ dataShow.nombre_ciudadano }}</label>
                    <label class="col-form-label col-md-8" v-else>@{{ dataShow.recibidode }}</label>
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
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Correo:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.email_ciudadano">@{{ dataShow.email_ciudadano }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Teléfono:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.telefono">@{{ dataShow.telefono }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Departamento:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.depto">@{{ dataShow.depto }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Ciudad:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.ciudad">@{{ dataShow.ciudad }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Dirección:
                        </strong></label>
                    <label class="col-form-label col-md-8">N/A</label>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>

<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Datos de destino</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Dependencia:</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.dependencia_destinataria }} </label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label
                        class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Funcionario'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.funcionario_destinatario }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label
                        class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Listado funcionario con copia:')</strong></label>
                    <label class="col-form-label col-md-8" v-html="dataShow.funcionario_copia"></label>
                </div>
            </div>

            <div class="col-md-6" v-if="dataShow.funcionario_compartir">
                <div class="form-group row m-b-15">
                    <label
                        class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Correspondencia compartida con'):</strong></label>
                    <label class="col-form-label col-md-8" v-html="dataShow.funcionario_compartir"></label>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Panel datos origen-->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
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
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Oficina Productora:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.oficina_productora">@{{ dataShow.oficina_productora }}</label>
                    <label class="col-form-label col-md-8" v-else>/N/A</label>
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
    <!-- end panel-body -->
</div>

<!-- Panel datos origen-->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos de la correspondencia</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Consecutivo:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.consecutivo">@{{ dataShow.consecutivo }}</label>
                    <label class="col-form-label col-md-8" v-else>/N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Asunto:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.asunto">@{{ dataShow.asunto }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Folios:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.folios">@{{ dataShow.folios }}</label>
                    <label class="col-form-label col-md-8" v-else>/N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>anexos:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.anexos">@{{ dataShow.anexos }}</label>
                    <label class="col-form-label col-md-8" v-else>N/A</label>

                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Tipo de Documento:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.tipodoc">@{{ dataShow.tipodoc }}</label>
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

        </div>

        <div class="row">
            <!-- Estado Field -->
            <label class="col-form-label col-md-2 text-black-transparent-7"><strong>Datos del asociado:
                        </strong></label>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.pqr ?? 'NA'}}.</dd>
        </div>

        <div class="row">
            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Adjunto del asociado:
                        </strong></label>
            <dd class="col-12" v-if="dataShow.adjunto_correspondence && dataShow.adjunto_correspondence.length > 0">
                <viewer-attachement :link-file-name="true" v-if="dataShow.adjunto_correspondence" :list="dataShow.adjunto_correspondence"></viewer-attachement>
            </dd>
            <dd v-else class="col-sm-9 col-md-9 col-lg-9">
                <span>No tiene adjunto</span>
            </dd>

        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- Panel datos origen-->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->

            <annotation-and-read :leido="dataShow.leido" route-annotation="get-annotations" campo="id_externa" table-annotations="externa_anotacion" :id-correspondence="dataShow.cf_id"></annotation-and-read>

    <!-- end panel-body -->
</div>
