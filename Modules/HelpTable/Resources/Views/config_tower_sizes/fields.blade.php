<!-- Size Field -->
<div class="form-group row m-b-15">
    {!! Form::label('size', trans('Tamaño').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('size', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.size }", 'v-model' => 'dataForm.size', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Size')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.size">
            <p class="m-b-0" v-for="error in dataErrors.size">@{{ error }}</p>
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