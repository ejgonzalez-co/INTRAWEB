
<!-- Anotacion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('anotacion', 'Observación:', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('anotacion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.anotacion }", 'v-model' => 'dataForm.anotacion', 'required' => true]) !!}
        <small>Ingrese una observsción </small>
        <div class="invalid-feedback" v-if="dataErrors.anotacion">
            <p class="m-b-0" v-for="error in dataErrors.anotacion">@{{ error }}</p>
        </div>
    </div>
</div>

