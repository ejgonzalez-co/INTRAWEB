<!-- Storage Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('storage_status', trans('Estado de almacenamiento').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('storage_status', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.storage_status }", 'v-model' => 'dataForm.storage_status', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('estado de almacenamiento')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.storage_status">
            <p class="m-b-0" v-for="error in dataErrors.storage_status">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status', trans('Estado').':', ['class' => 'col-form-label col-md-3 required']) !!}
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