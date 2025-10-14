<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Información inicial</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Nombre Ruta Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('nombre_ruta', trans('Nombre de la ruta').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('nombre_ruta', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_ruta }", 'v-model' => 'dataForm.nombre_ruta', 'required' => true]) !!}
                <small>@lang('Enter the') el @{{ `@lang('Nombre de la Ruta')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.nombre_ruta">
                    <p class="m-b-0" v-for="error in dataErrors.nombre_ruta">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Descripcion Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('descripcion', trans('Descripcion').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion }", 'v-model' => 'dataForm.descripcion', 'required' => true]) !!}
                <small>@lang('Enter the') la @{{ `@lang('Descripcion')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.descripcion">
                    <p class="m-b-0" v-for="error in dataErrors.descripcion">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Dependencias asociadas a la ruta</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <dynamic-list label-button-add="Agregar dependencia" :data-list.sync="dataForm.planilla_ruta_dependencias" :is-remove="true"
            :data-list-options="[
                { label: 'Dependencia', name: 'dependencias_id', isShow: true, nameObjectKey: ['dependencias', 'nombre'], refList: 'dependencias_ref' }
            ]"
            class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
            <template #fields="scope">
                <!-- Dependencia Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('dependencia', trans('Dependencia') . ':', ['class' => 'col-form-label col-md-3',]) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="dependencias_id" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="scope.dataForm" :is-required="true" ref-select-check="dependencias_ref" :enable-search="true" name-field-object="dependencias"></select-check>
                        <div class="invalid-feedback" v-if="dataErrors.dependencia">
                            <p class="m-b-0" v-for="error in dataErrors.dependencia">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-list>

    </div>
    <!-- end panel-body -->
</div>
