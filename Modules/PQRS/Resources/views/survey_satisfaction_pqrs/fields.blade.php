<!-- Respuesta1 Field -->
<div class="form-group row m-b-15">
    {!! Form::label('respuesta1', '1. ¿ La accesibilidad al canal de atención para instaurar una PQRSDF mediante el aplicativo suministrado en el Portal de ' . $nombreEntidad . ', le pareció sencilla y eficaz?:', ['class' => 'col-form-label col-md-3 required',  'style' => 'text-align: justify;']) !!}
    <div class="col-md-9">
        {!! Form::select('respuesta1', ["Si" => "Si", "No" => "No"], "respuesta1", [':class' => "{'form-control':true, 'is-invalid':dataErrors.seguridad }", 'v-model' => 'dataForm.respuesta1' , 'required' => true ]) !!}
                        <small>"Seleccione 'Sí' si considera que el acceso al canal de atención fue sencillo y eficaz, o 'No' si tuvo dificultades o no fue eficiente."</small>
        <div class="invalid-feedback" v-if="dataErrors.respuesta1">
            <p class="m-b-0" v-for="error in dataErrors.respuesta1">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Respuesta2 Field -->
<div class="form-group row m-b-15">
    {!! Form::label('respuesta2', '2. ¿ La respuesta al PQRSDF fue oportuna ?:', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::select('respuesta2', ["Si" => "Si", "No" => "No"], "respuesta2", [':class' => "{'form-control':true, 'is-invalid':dataErrors.seguridad }", 'v-model' => 'dataForm.respuesta2', 'required' => true]) !!}
                        <small>"Seleccione 'Sí' si considera que la respuesta fue proporcionada dentro del tiempo esperado, o 'No' si no fue oportuna."</small>
                        
        <div class="invalid-feedback" v-if="dataErrors.respuesta2">
            <p class="m-b-0" v-for="error in dataErrors.respuesta2">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Respuesta3 Field -->
<div class="form-group row m-b-15">
    {!! Form::label('respuesta3','3. ¿La respuesta a su pregunta fue acertada y resolvió la inquietud planteada?:', ['class' => 'col-form-label col-md-3 required',  'style' => 'text-align: justify;']) !!}
    <div class="col-md-9">
        {!! Form::select('respuesta3', ["Si" => "Si", "No" => "No"], "respuesta3", [':class' => "{'form-control':true, 'is-invalid':dataErrors.seguridad }", 'v-model' => 'dataForm.respuesta3', 'required' => true]) !!}
                        <small>"Seleccione 'Sí' si la respuesta fue adecuada y resolvió su inquietud, o 'No' si no fue clara o no resolvió su pregunta."</small>
        <div class="invalid-feedback" v-if="dataErrors.respuesta3">
            <p class="m-b-0" v-for="error in dataErrors.respuesta3">@{{ error }}</p>
        </div>
    </div>
</div>
