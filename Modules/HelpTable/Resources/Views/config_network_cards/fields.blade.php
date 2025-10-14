<!-- Network Card Field -->
<div class="form-group row m-b-15">
    {!! Form::label('network_card', trans('Tarjeta de red').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('network_card', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.network_card }", 'v-model' => 'dataForm.network_card', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Network Card')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.network_card">
            <p class="m-b-0" v-for="error in dataErrors.network_card">@{{ error }}</p>
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