<!-- Id Solicitud Necesidad Field -->
<div class="form-group row m-b-15">
    {!! Form::label('id_solicitud_necesidad', trans('Id Solicitud Necesidad').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('id_solicitud_necesidad', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.id_solicitud_necesidad }", 'v-model' => 'dataForm.id_solicitud_necesidad', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Id Solicitud Necesidad')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_solicitud_necesidad">
            <p class="m-b-0" v-for="error in dataErrors.id_solicitud_necesidad">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Codigo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('codigo', trans('Codigo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('codigo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.codigo }", 'v-model' => 'dataForm.codigo', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Codigo')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.codigo">
            <p class="m-b-0" v-for="error in dataErrors.codigo">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Articulo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('articulo', trans('Articulo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('articulo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.articulo }", 'v-model' => 'dataForm.articulo', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Articulo')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.articulo">
            <p class="m-b-0" v-for="error in dataErrors.articulo">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Grupo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('grupo', trans('Grupo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('grupo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.grupo }", 'v-model' => 'dataForm.grupo', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Grupo')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.grupo">
            <p class="m-b-0" v-for="error in dataErrors.grupo">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Cantidad Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cantidad', trans('Cantidad').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('cantidad', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cantidad }", 'v-model' => 'dataForm.cantidad', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Cantidad')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cantidad">
            <p class="m-b-0" v-for="error in dataErrors.cantidad">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Costo Unitario Field -->
<div class="form-group row m-b-15">
    {!! Form::label('costo_unitario', trans('Costo Unitario').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('costo_unitario', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.costo_unitario }", 'v-model' => 'dataForm.costo_unitario', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Costo Unitario')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.costo_unitario">
            <p class="m-b-0" v-for="error in dataErrors.costo_unitario">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Total Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total', trans('Total').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('total', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.total }", 'v-model' => 'dataForm.total', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Total')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.total">
            <p class="m-b-0" v-for="error in dataErrors.total">@{{ error }}</p>
        </div>
    </div>
</div>
