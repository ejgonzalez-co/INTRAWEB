<!-- Point Location Field -->
<div class="form-group row m-b-15">
    {!! Form::label('point_location', trans('Point Location').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('point_location', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.point_location }", 'v-model' => 'dataForm.point_location', 'required' => true]) !!}
        <small>Ingrese el nombre de la ubicación del punto de muestra</small>
        <div class="invalid-feedback" v-if="dataErrors.point_location">
            <p class="m-b-0" v-for="error in dataErrors.point_location">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- No Samples Taken Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_samples_taken', trans('No Samples Taken').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_samples_taken', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_samples_taken }", 'v-model' => 'dataForm.no_samples_taken', 'required' => false]) !!}
        <small>Ingrese el número de muestras tomadas en el punto</small>
        <div class="invalid-feedback" v-if="dataErrors.no_samples_taken">
            <p class="m-b-0" v-for="error in dataErrors.no_samples_taken">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Sector Field -->
<div class="form-group row m-b-15">
    {!! Form::label('sector', trans('Sector').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('sector', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.sector }", 'v-model' => 'dataForm.sector', 'required' => true]) !!}
        <small>Ingrese el sector del muestreo</small>
        <div class="invalid-feedback" v-if="dataErrors.sector">
            <p class="m-b-0" v-for="error in dataErrors.sector">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tank Feeding Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tank_feeding', trans('Tank Feeding').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tank_feeding', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tank_feeding }", 'v-model' => 'dataForm.tank_feeding', 'required' => true]) !!}
        <small>Ingrese el nombre del tanque que alimenta</small>
        <div class="invalid-feedback" v-if="dataErrors.tank_feeding">
            <p class="m-b-0" v-for="error in dataErrors.tank_feeding">@{{ error }}</p>
        </div>
    </div>
</div>



<!-- No Samples Taken Field -->
<div class="form-group row m-b-15">
    {!! Form::label('code', trans('Código punto de muestra').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('code', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.code }", 'v-model' => 'dataForm.code', 'required' => true]) !!}
        <small>Ingrese el código punto de muestra</small>
        <div class="invalid-feedback" v-if="dataErrors.code">
            <p class="m-b-0" v-for="error in dataErrors.code">@{{ error }}</p>
        </div>
    </div>
</div>
