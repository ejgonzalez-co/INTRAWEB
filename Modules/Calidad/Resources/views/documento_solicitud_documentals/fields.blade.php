<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Calidad Macroproceso Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('calidad_macroproceso_id', trans('Macroproceso/Sistema').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="calidad_macroproceso_id" reduce-label="nombre" name-resource="get-solo-procesos-activos" :value="dataForm" :is-required="true"></select-check>
                <small>Seleccione el macroproceso, laboratorio o sistema.</small>
                <div class="invalid-feedback" v-if="dataErrors.calidad_macroproceso_id">
                    <p class="m-b-0" v-for="error in dataErrors.calidad_macroproceso_id">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Users Id Solicitante Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('users_id_solicitante', trans('Nombre del solicitante').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <autocomplete
                    name-prop="name"
                    name-field="users_id_solicitante"
                    :value='dataForm'
                    name-resource="/calidad/get-usuarios"
                    css-class="form-control"
                    :is-required="true"
                    :name-labels-display="['fullname']"
                    reduce-key="id"
                    :key="keyRefresh"
                    name-field-edit="nombre_solicitante"
                    name-field-object="users_id_solicitante_object"
                    :activar-blur="true">
                </autocomplete>
                <small>@lang('Enter the') el @{{ `@lang('nombre del solicitante')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.users_id_solicitante">
                    <p class="m-b-0" v-for="error in dataErrors.users_id_solicitante">@{{ error }}</p>
                </div>
            </div>
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
        <!-- Tipo Solicitud Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('tipo_solicitud', trans('Tipo de solicitud').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-3">
                {!! Form::select('tipo_solicitud', ["Elaboración" => "Elaboración", "Modificación" => "Modificación", "Eliminación" => "Eliminación", "Actualización normograma" => "Actualización normograma"], 'Seleccione', [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_solicitud }", 'v-model' => 'dataForm.tipo_solicitud', 'required' => true]) !!}
                <small>@lang('Select the') el @{{ `@lang('tipo de solicitud')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.tipo_solicitud">
                    <p class="m-b-0" v-for="error in dataErrors.tipo_solicitud">@{{ error }}</p>
                </div>
            </div>

            <!-- Tipo Documento Field -->
            {!! Form::label('tipo_documento', trans('Tipo de documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="tipo_documento" reduce-label="nombre" name-resource="get-tipo-documentos-activos" :value="dataForm" :is-required="true"></select-check>
                <small>@lang('Select the') el @{{ `@lang('tipo de documento')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.tipo_documento">
                    <p class="m-b-0" v-for="error in dataErrors.tipo_documento">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Codigo Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('codigo', trans('Código del documento').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('codigo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.codigo }", 'v-model' => 'dataForm.codigo', 'required' => false]) !!}
                <small>Ingrese el código del documento.</small>
                <div class="invalid-feedback" v-if="dataErrors.codigo">
                    <p class="m-b-0" v-for="error in dataErrors.codigo">@{{ error }}</p>
                </div>
            </div>

            <span class="col-md-6 p-r-0 form-group row m-b-15" v-if="dataForm.tipo_solicitud != 'Elaboración'">
                <!-- Calidad Documento Id Field -->
                {!! Form::label('calidad_documento_id', trans('Nombre del documento').':', ['class' => 'col-form-label col-md-6 required']) !!}
                <div class="col-md-6 p-r-0 p-l-15">
                    <autocomplete
                        name-prop="titulo"
                        name-field="calidad_documento_id"
                        :value='dataForm'
                        name-resource="obtener-documentos-publicos"
                        css-class="form-control"
                        :is-required="true"
                        :name-labels-display="['titulo']"
                        reduce-key="id"
                        :key="dataForm.tipo_solicitud"
                        name-field-edit="calidad_documento_id"
                        name-field-object="calidad_documento_id_object"
                        :activar-blur="true">
                    </autocomplete>
                    <small>Ingrese el nombre del documento.</small>
                    <div class="invalid-feedback" v-if="dataErrors.calidad_documento_id">
                        <p class="m-b-0" v-for="error in dataErrors.calidad_documento_id">@{{ error }}</p>
                    </div>
                </div>
            </span>

            <span class="col-md-6 p-r-0 form-group row m-b-15" v-else>
                <!-- Nombre Documento Field -->
                {!! Form::label('nombre_documento', trans('Nombre del documento').':', ['class' => 'col-form-label col-md-6 required']) !!}
                <div class="col-md-6 p-r-0 p-l-15">
                    {!! Form::text('nombre_documento', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_documento }", 'v-model' => 'dataForm.nombre_documento', 'required' => true]) !!}
                    <small>Ingrese el nombre del documento.</small>
                    <div class="invalid-feedback" v-if="dataErrors.nombre_documento">
                        <p class="m-b-0" v-for="error in dataErrors.nombre_documento">@{{ error }}</p>
                    </div>
                </div>
            </span>
        </div>

        <!-- Calidad Proceso Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('calidad_proceso_id', trans('Proceso al que pertenece').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="calidad_proceso_id" reduce-label="nombre" :name-resource="'get-procesos-activos-macro/'+dataForm.calidad_macroproceso_id" :value="dataForm" :is-required="true" :key="dataForm.calidad_macroproceso_id"></select-check>
                <small>Seleccione el proceso al que pertenece, de acuerdo al macroproceso.</small>
                <div class="invalid-feedback" v-if="dataErrors.calidad_proceso_id">
                    <p class="m-b-0" v-for="error in dataErrors.calidad_proceso_id">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Justificacion Solicitud Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('justificacion_solicitud', trans('Justificacion Solicitud').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('justificacion_solicitud', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.justificacion_solicitud }", 'v-model' => 'dataForm.justificacion_solicitud', 'required' => true]) !!}
                <small>Seleccione la justificación o necesidad de la solicitud de elaboración o modificación.</small>
                <div class="invalid-feedback" v-if="dataErrors.justificacion_solicitud">
                    <p class="m-b-0" v-for="error in dataErrors.justificacion_solicitud">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Version Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('version', trans('Versión').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-3">
                {!! Form::number('version', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.version }", 'v-model' => 'dataForm.version', 'required' => true]) !!}
                <small>Ingrese la versión del documento.</small>
                <div class="invalid-feedback" v-if="dataErrors.version">
                    <p class="m-b-0" v-for="error in dataErrors.version">@{{ error }}</p>
                </div>
            </div>

            <!-- Adjunto Field -->
            {!! Form::label('adjunto', trans('Adjunto').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::file('adjunto', ['accept' => '*', '@change' => 'inputFile($event, "adjunto")', 'required' => false]) !!}
                <small>Seleccione un adjunto de máximo 5Mb.</small>
                <div class="invalid-feedback" v-if="dataErrors.adjunto">
                    <p class="m-b-0" v-for="error in dataErrors.adjunto">@{{ error }}</p>
                </div>
            </div>
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
        <!-- Users Id Responsable Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('users_id_responsable', trans('Funcionario responsable de atender la solicitud').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <autocomplete
                    name-prop="name"
                    name-field="users_id_responsable"
                    :value='dataForm'
                    name-resource="/calidad/get-usuarios"
                    css-class="form-control"
                    :is-required="true"
                    :name-labels-display="['fullname']"
                    reduce-key="id"
                    :key="keyRefresh"
                    name-field-edit="funcionario_responsable"
                    name-field-object="users_id_responsable_object"
                    :activar-blur="true">
                </autocomplete>
                <small>Ingrese el funcionario responsable de atender la solicitud.</small>
                <div class="invalid-feedback" v-if="dataErrors.users_id_responsable">
                    <p class="m-b-0" v-for="error in dataErrors.users_id_responsable">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
