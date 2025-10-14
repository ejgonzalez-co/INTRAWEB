<!-- Nombre Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre', trans('Nombre').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }", 'v-model' => 'dataForm.nombre', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nombre')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre">
            <p class="m-b-0" v-for="error in dataErrors.nombre">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tipo Campo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tipo_campo', trans('Tipo Campo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tipo_campo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_campo }", 'v-model' => 'dataForm.tipo_campo', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tipo Campo')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tipo_campo">
            <p class="m-b-0" v-for="error in dataErrors.tipo_campo">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Texto Ayuda Field -->
<div class="form-group row m-b-15">
    {!! Form::label('texto_ayuda', trans('Texto Ayuda').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('texto_ayuda', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.texto_ayuda }", 'v-model' => 'dataForm.texto_ayuda', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Texto Ayuda')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.texto_ayuda">
            <p class="m-b-0" v-for="error in dataErrors.texto_ayuda">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Requerido Field -->
<div class="form-group row m-b-15">
    {!! Form::label('requerido', trans('Requerido').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('requerido', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.requerido }", 'v-model' => 'dataForm.requerido', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Requerido')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.requerido">
            <p class="m-b-0" v-for="error in dataErrors.requerido">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Opciones Field -->
<div class="form-group row m-b-15">
    {!! Form::label('opciones', trans('Opciones').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('opciones', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.opciones }", 'v-model' => 'dataForm.opciones', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Opciones')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.opciones">
            <p class="m-b-0" v-for="error in dataErrors.opciones">@{{ error }}</p>
        </div>
    </div>
</div>