<!-- Nombre Sistema Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre_sistema', trans('Nombre del sistema').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre_sistema', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_sistema }", 'v-model' => 'dataForm.nombre_sistema', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Nombre del Sistema')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre_sistema">
            <p class="m-b-0" v-for="error in dataErrors.nombre_sistema">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Descripcion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('descripcion', trans('Descripción').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion }", 'v-model' => 'dataForm.descripcion']) !!}
        <small>@lang('Enter the') una @{{ `@lang('Descripción')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.descripcion">
            <p class="m-b-0" v-for="error in dataErrors.descripcion">@{{ error }}</p>
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
