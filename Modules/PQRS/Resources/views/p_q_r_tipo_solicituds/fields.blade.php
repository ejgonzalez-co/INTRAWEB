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

<!-- Descripcion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('descripcion', trans('Descripcion').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion }", 'v-model' => 'dataForm.descripcion', 'required' => false]) !!}
        <small>@lang('Enter the') la @{{ `@lang('Descripcion')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.descripcion">
            <p class="m-b-0" v-for="error in dataErrors.descripcion">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Estado Field -->
<div class="form-group row m-b-15">
    {!! Form::label('estado', trans('Estado').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::select('estado', ["Activo" => "Activo", "Inactivo" => "Inactivo"], 'Activo', [':class' => "{'form-control':true, 'is-invalid':dataErrors.estado }", 'v-model' => 'dataForm.estado', 'required' => true]) !!}
        <small>@lang('Select the') el @{{ `@lang('Estado')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.estado">
            <p class="m-b-0" v-for="error in dataErrors.estado">@{{ error }}</p>
        </div>
    </div>
</div>