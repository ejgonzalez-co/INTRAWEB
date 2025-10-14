@if (isset($clasificacion) && $clasificacion === 'si')
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Clasificación documental</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- classification_production_office Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('classification_production_office', 'Oficina productora: ', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">

                        <select-check css-class="form-control" name-field="classification_production_office" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="dataForm" :enable-search="true" :ids-to-empty="['classification_serie','classification_subserie']" :is-required="true"></select-check>
                        <small>Seleccione la oficina productora.</small>

                        <div class="invalid-feedback" v-if="dataErrors.classification_production_office">
                            <p class="m-b-0" v-for="error in dataErrors.classification_production_office">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- classification_serie Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('classification_serie', 'Serie: ', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">

                        <select-check css-class="form-control" name-field="classification_serie" reduce-label="name" reduce-key="id_series_subseries"
                        :name-resource="'/documentary-classification/get-inventory-documentals-serie-dependency/'+ dataForm.classification_production_office"
                        :value="dataForm" :enable-search="true" :key="dataForm.classification_production_office" :is-required="true"></select-check>
                        <small>Seleccione la serie relacionada, ejemplo oficio.</small>

                        <div class="invalid-feedback" v-if="dataErrors.classification_serie">
                            <p class="m-b-0" v-for="error in dataErrors.classification_serie">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- classification_subserie Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('classification_subserie', 'Subserie: ', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">

                        <select-check css-class="form-control" name-field="classification_subserie" reduce-label="name_subserie" :name-resource="'/documentary-classification/get-subseries-clasificacion?serie='+dataForm.classification_serie" :value="dataForm" :key="dataForm.classification_serie" :enable-search="true" :is-required="false"></select-check>
                        <small>Seleccione la sub-serie relacionada, ejemplo oficio de apertura.</small>
                        <div class="invalid-feedback" v-if="dataErrors.classification_subserie">
                            <p class="m-b-0" v-for="error in dataErrors.classification_subserie">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>
@endif
