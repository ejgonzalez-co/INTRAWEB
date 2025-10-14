<!-- Calidad Tipo Sistema Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calidad_tipo_sistema_id', trans('Tipo de sistema').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="calidad_tipo_sistema_id" reduce-label="nombre_sistema" name-resource="get-tipo-sistemas-activos" :value="dataForm" :is-required="true"></select-check>
        <small>@lang('Select the') el @{{ `@lang('Tipo de Sistema')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.calidad_tipo_sistema_id">
            <p class="m-b-0" v-for="error in dataErrors.calidad_tipo_sistema_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Nombre Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre', trans('Nombre').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }", 'v-model' => 'dataForm.nombre', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nombre')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre">
            <p class="m-b-0" v-for="error in dataErrors.nombre">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Prefijo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('prefijo', trans('Prefijo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('prefijo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.prefijo }", 'v-model' => 'dataForm.prefijo', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Prefijo')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.prefijo">
            <p class="m-b-0" v-for="error in dataErrors.prefijo">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Dependencias Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('dependencias_id', trans('Dependencia').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="dependencias_id" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
        <small>@lang('Select the') la @{{ `@lang('dependencia')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.dependencias_id">
            <p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Calidad Tipo Proceso Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calidad_tipo_proceso_id', trans('Tipo de proceso').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="calidad_tipo_proceso_id" reduce-label="nombre" name-resource="get-tipo-procesos" :value="dataForm" :is-required="false" :enable-search="true"></select-check>
        <small>@lang('Select the') el @{{ `@lang('tipo de proceso')` | lowercase }} (Macroproceso)</small>
        <div class="invalid-feedback" v-if="dataErrors.calidad_tipo_proceso_id">
            <p class="m-b-0" v-for="error in dataErrors.calidad_tipo_proceso_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Calidad Proceso Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calidad_proceso_id', trans('Proceso padre').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="calidad_proceso_id" reduce-label="nombre" name-resource="obtener-procesos" :value="dataForm" :is-required="false" :enable-search="true"></select-check>
        <small>@lang('Select the') el @{{ `@lang('proceso padre')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.calidad_proceso_id">
            <p class="m-b-0" v-for="error in dataErrors.calidad_proceso_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Estado Field -->
<div class="form-group row m-b-15">
    {!! Form::label('estado', trans('Estado').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select class="form-control" name="estado" id="estado" v-model="dataForm.estado" required>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>
        <small>@lang('Select the') el @{{ `@lang('Estado del tipo de documento')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.estado">
            <p class="m-b-0" v-for="error in dataErrors.estado">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Id Responsable Field -->
<div class="form-group row m-b-15">
    {!! Form::label('id_responsable', trans('Responsable').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="id_responsable" reduce-label="usuario_cargo" name-resource="obtener-responsables" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
        <small>@lang('Enter the') el @{{ `@lang('Responsable')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_responsable">
            <p class="m-b-0" v-for="error in dataErrors.id_responsable">@{{ error }}</p>
        </div>
    </div>
</div>
