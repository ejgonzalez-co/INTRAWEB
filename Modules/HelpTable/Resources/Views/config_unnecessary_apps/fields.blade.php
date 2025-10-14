<!-- Unnecessary App Field -->
<div class="form-group row m-b-15">
    {!! Form::label('unnecessary_app', trans('Aplicación no necesaria').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('unnecessary_app', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.unnecessary_app }", 'v-model' => 'dataForm.unnecessary_app', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Aplicación no necesaria')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.unnecessary_app">
            <p class="m-b-0" v-for="error in dataErrors.unnecessary_app">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status', trans('Status').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.status" name="status">
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>
        <small>@lang('Enter the') @{{ `@lang('Status')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.status">
            <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
        </div>
    </div>
</div>