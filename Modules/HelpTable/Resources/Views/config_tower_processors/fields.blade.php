<!-- Processor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('processor', trans('Processor').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('processor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.processor }", 'v-model' => 'dataForm.processor', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Processor')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.processor">
            <p class="m-b-0" v-for="error in dataErrors.processor">@{{ error }}</p>
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