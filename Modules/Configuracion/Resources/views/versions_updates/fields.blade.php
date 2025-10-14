<!-- Tipo De Cambio Field -->
<div class="form-group row m-b-15" v-if="!isUpdate">
    {!! Form::label('tipo_de_cambio', 'Tipo de lanzamiento'.':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.tipo_de_cambio" name="tipo_de_cambio">
            <option value="Mejora Estructural">Mejora Estructural</option>
            <option value="Mejora Mediana">Mejora Mediana</option>
            <option value="Mejora Pequeña">Mejora Pequeña</option>
        </select>
        <small>@lang('Select the') el tipo de cambio que se descargo</small>
        <div class="invalid-feedback" v-if="dataErrors.tipo_de_cambio">
            <p class="m-b-0" v-for="error in dataErrors.tipo_de_cambio">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Descripcion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('descripcion', 'Descripción'.':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion }", 'v-model' => 'dataForm.descripcion', 'required' => true]) !!}
        <small>@lang('Enter the') la descripción del lanzamiento</small>
        <div class="invalid-feedback" v-if="dataErrors.descripcion">
            <p class="m-b-0" v-for="error in dataErrors.descripcion">@{{ error }}</p>
        </div>
    </div>
</div>