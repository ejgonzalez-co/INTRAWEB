<!-- Nombre Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre', trans('Nombre').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }", 'v-model' => 'dataForm.nombre', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Nombre')` | lowercase }}</small>
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

<!-- Orden Field -->
<div class="form-group row m-b-15">
    {!! Form::label('orden', trans('Orden').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('orden', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.orden }", 'v-model' => 'dataForm.orden', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Orden')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.orden">
            <p class="m-b-0" v-for="error in dataErrors.orden">@{{ error }}</p>
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
        <small>@lang('Select the') el @{{ `@lang('Estado del tipo de sistema')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.estado">
            <p class="m-b-0" v-for="error in dataErrors.estado">@{{ error }}</p>
        </div>
    </div>
</div>

