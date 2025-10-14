<!-- Reference Field -->
<div class="form-group row m-b-15">
    {!! Form::label('reference', trans('Reference').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('reference', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.reference }", 'v-model' => 'dataForm.reference', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Reference')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.reference">
            <p class="m-b-0" v-for="error in dataErrors.reference">@{{ error }}</p>
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
        <div class="invalid-feedback" v-if="dataErrors.status">
            <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
        </div>
    </div>
</div>