<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Datos de origen</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Dependencia:</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.dependencia_remitente }} </label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label
                        class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Funcionario'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.funcionario_remitente }}</label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panel datos origen-->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos de destino</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Enviado a:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.funcionario_remitente">@{{ dataShow.funcionario_remitente }} - @{{ dataShow.dependencia_remitente  }}</label>
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
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Correspondencia compartida con:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.funcionario_compartir" v-html="dataShow.funcionario_compartir"></label>
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
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Plantilla:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.tipodoc">@{{ dataShow.tipodoc }}</label>
                    <label class="col-form-label col-md-8" v-else>/N/A</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Estado:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.estado">@{{ dataShow.estado }}</label>
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
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Folios:
                        </strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.folios">@{{ dataShow.folios }}</label>
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

            <annotation-and-read :leido="dataShow.leido" campo="id_interna" route-annotation="get-annotations" table-annotations="interna_anotacion" :id-correspondence="dataShow.cf_id"></annotation-and-read>

    <!-- end panel-body -->
</div>
