<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>Ingrese aquí el nombre del tipo de activo.</small>
    </div>
</div>

<!-- Form type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('form_type', trans('Tipo de formulario').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.form_type" name="form_type" id="form_type" required :disabled="isUpdate">
            <option value="">Seleccione</option>
            <option value="1">Formulario hoja de vida de los vehículos y maquinaria amarilla</option>
            <option value="2">Formulario hoja de vida de los equipos menores</option>
            <option value="3">Formulario hoja de vida plantas y medidores</option>
            <option value="4">Formulario hoja de vida del equipamiento (LECA)</option>
            <option value="5">Formulario inventario y cronograma del aseguramiento metrológico</option>
        </select>
        <small>Seleccione el formulario de hoja de vida para la creación de este activo.</small>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>Ingrese aquí la descripción del tipo de activo.</small>
    </div>
</div>