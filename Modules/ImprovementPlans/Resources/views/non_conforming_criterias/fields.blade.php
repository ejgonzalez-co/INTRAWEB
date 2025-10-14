<!-- Description Cause Analysis Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description_cause_analysis', trans('Descripción del análisis de las causas').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description_cause_analysis', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description_cause_analysis }", 'v-model' => 'dataForm.description_cause_analysis', 'required' => true]) !!}
        <small>Ingrese la descripción del análisis de las causas</small>
        <div class="invalid-feedback" v-if="dataErrors.description_cause_analysis">
            <p class="m-b-0" v-for="error in dataErrors.description_cause_analysis">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Possible Causes Field -->
<div class="form-group row m-b-15">
    {!! Form::label('possible_causes', trans('Listar posibles causas').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('possible_causes', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.possible_causes }", 'v-model' => 'dataForm.possible_causes', 'required' => true]) !!}
        <small>Ingrese posibles causas</small>
        <div class="invalid-feedback" v-if="dataErrors.possible_causes">
            <p class="m-b-0" v-for="error in dataErrors.possible_causes">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Weight Field -->
<div class="form-group row m-b-15">
    {!! Form::label('weight', trans('Peso de la oportunidad de mejora en el plan').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">

            <div class="input-group">
                {!! Form::number('weight', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.weight }", 'v-model' => 'dataForm.weight','required' => true,
                'min' => 1,
                'max' => 100,]) !!}
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
            <small>Ingrese el Peso de la oportunidad de mejora con respecto al plan.</small>

    </div>
</div>