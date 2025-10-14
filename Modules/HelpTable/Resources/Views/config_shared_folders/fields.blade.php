<!-- Shared Folder Field -->
<div class="form-group row m-b-15">
    {!! Form::label('shared_folder', trans('Carpeta Compartida').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('shared_folder', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.shared_folder }", 'v-model' => 'dataForm.shared_folder', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Carpeta Compartida')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.shared_folder">
            <p class="m-b-0" v-for="error in dataErrors.shared_folder">@{{ error }}</p>
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