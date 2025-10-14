<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Información inicial</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Codigo Field -->
            {!! Form::label('codigo', trans('Código').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('codigo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.codigo }", 'v-model' => 'dataForm.codigo', 'required' => true]) !!}
                <small>@lang('Enter the') el @{{ `@lang('Codigo')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.codigo">
                    <p class="m-b-0" v-for="error in dataErrors.codigo">@{{ error }}</p>
                </div>
            </div>
            <!-- Nombre Field -->
            {!! Form::label('nombre', trans('Nombre').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('nombre', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }", 'v-model' => 'dataForm.nombre', 'required' => true]) !!}
                <small>@lang('Enter the') el @{{ `@lang('Nombre')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.nombre">
                    <p class="m-b-0" v-for="error in dataErrors.nombre">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Descripcion Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('descripcion', trans('Descripcion').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-10">
                {!! Form::textarea('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion }", 'v-model' => 'dataForm.descripcion', 'required' => true]) !!}
                <small>@lang('Enter the') la @{{ `@lang('Descripcion')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.descripcion">
                    <p class="m-b-0" v-for="error in dataErrors.descripcion">@{{ error }}</p>
                </div>
            </div>
        </div>


        <div class="form-group row m-b-15">
            <!-- Tipo Plazo Field -->
            {!! Form::label('tipo_plazo', trans('Tipo de plazo').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::select('tipo_plazo', ["Laboral" => "Laboral", "Calendario" => "Calendario"], 'Laboral', [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_plazo }", 'v-model' => 'dataForm.tipo_plazo', 'required' => true]) !!}
                <small>@lang('Select the') el tipo de plazo</small>
                <div class="invalid-feedback" v-if="dataErrors.tipo_plazo">
                    <p class="m-b-0" v-for="error in dataErrors.tipo_plazo">@{{ error }}</p>
                </div>
            </div>
            <!-- Estado Field -->
            {!! Form::label('estado', trans('Estado').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::select('estado', ["Activo" => "Activo", "Inactivo" => "Inactivo"], 'Activo', [':class' => "{'form-control':true, 'is-invalid':dataErrors.estado }", 'v-model' => 'dataForm.estado', 'required' => true]) !!}
                <small>@lang('Select the') el @{{ `@lang('Estado')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.estado">
                    <p class="m-b-0" v-for="error in dataErrors.estado">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Plazo Field -->
            {!! Form::label('plazo', trans('Plazo').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('plazo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.plazo }", 'v-model' => 'dataForm.plazo', 'required' => true]) !!}
                <small>@lang('Enter the') el @{{ `@lang('Plazo')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.plazo">
                    <p class="m-b-0" v-for="error in dataErrors.plazo">@{{ error }}</p>
                </div>
            </div>
            <!-- Plazo Unidad Field -->
            {!! Form::label('plazo_unidad', trans('Unidad del plazo').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::select('plazo_unidad', ["Días" => "Días", "Horas" => "Horas"], 'Días', [':class' => "{'form-control':true, 'is-invalid':dataErrors.plazo_unidad }", 'v-model' => 'dataForm.plazo_unidad', 'required' => true]) !!}
                <small>@lang('Select the') la unidad del plazo</small>
                <div class="invalid-feedback" v-if="dataErrors.plazo_unidad">
                    <p class="m-b-0" v-for="error in dataErrors.plazo_unidad">@{{ error }}</p>
                </div>
            </div>
        </div>


        <div class="form-group row m-b-15">
            <!-- Temprana Field -->
            {!! Form::label('temprana', trans('Alerta temprana').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('temprana', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.temprana }", 'v-model' => 'dataForm.temprana', 'required' => true]) !!}
                <small>@lang('Enter the') la alerta temprana</small>
                <div class="invalid-feedback" v-if="dataErrors.temprana">
                    <p class="m-b-0" v-for="error in dataErrors.temprana">@{{ error }}</p>
                </div>
            </div>
            <!-- Temprana Unidad Field -->
            {!! Form::label('temprana_unidad', trans('Unidad de la alerta temprana').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::select('temprana_unidad', ["Días" => "Días", "Horas" => "Horas"], 'Días', [':class' => "{'form-control':true, 'is-invalid':dataErrors.temprana_unidad }", 'v-model' => 'dataForm.temprana_unidad', 'required' => true]) !!}
                <small>@lang('Select the') la unidad de la alerta temprana</small>
                <div class="invalid-feedback" v-if="dataErrors.temprana_unidad">
                    <p class="m-b-0" v-for="error in dataErrors.temprana_unidad">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Dependencias asociadas al eje temático</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <dynamic-list label-button-add="Agregar dependencia" :data-list.sync="dataForm.ejetematico_has_dependencias" :is-remove="true"
            :data-list-options="[
                { label: 'Dependencia', name: 'dependencias_id', nameObjectKey: ['dependencias', 'nombre'], isShow: true, refList: 'dependencias_ref' }
            ]"
            class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
            <template #fields="scope">
                <!-- Dependencia Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('dependencia', trans('Dependencia') . ':', ['class' => 'col-form-label col-md-3',]) !!}
                    <div class="col-md-4">
                        <select-check css-class="form-control" name-field="dependencias_id" name-field-object="dependencias" reduce-label="nombre" name-resource="get-dependencies" :value="scope.dataForm" :is-required="true" ref-select-check="dependencias_ref" :enable-search="false" ></select-check>
                        <div class="invalid-feedback" v-if="dataErrors.dependencia">
                            <p class="m-b-0" v-for="error in dataErrors.dependencia">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-list>

    </div>
    <!-- end panel-body -->
</div> --}}
