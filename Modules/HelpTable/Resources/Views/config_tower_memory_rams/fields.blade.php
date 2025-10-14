<!-- Memory Ram Field -->
<div class="form-group row m-b-15">
    {!! Form::label('memory_ram', trans('Memoria RAM').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('memory_ram', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.memory_ram }", 'v-model' => 'dataForm.memory_ram', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Memoria RAM')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.memory_ram">
            <p class="m-b-0" v-for="error in dataErrors.memory_ram">@{{ error }}</p>
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