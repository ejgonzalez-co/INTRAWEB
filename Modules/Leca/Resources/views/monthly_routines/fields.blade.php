<!-- Routine Start Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('routine_start_date', trans('Routine Start Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="routine_start_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>Seleccione la fecha de inicio de la rutina mensual</small>
        <div class="invalid-feedback" v-if="dataErrors.routine_start_date">
            <p class="m-b-0" v-for="error in dataErrors.routine_start_date">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Routine End Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('routine_end_date', trans('Routine End Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="routine_end_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>Seleccione la fecha final de la rutina mensual</small>
        <div class="invalid-feedback" v-if="dataErrors.routine_end_date">
            <p class="m-b-0" v-for="error in dataErrors.routine_end_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- State Routine Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state_routine', trans('State Routine').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::select('state_routine',['Abierta'=>'Abierta','Cerrada'=>'Cerrada'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.state_routine }", 'v-model' => 'dataForm.state_routine', 'required' => true]) !!}
        <small>Seleccione el tipo de solicitud que desea realizar</small>
        <div class="invalid-feedback" v-if="dataErrors.state_routine">
            <p class="m-b-0" v-for="error in dataErrors.state_routine">@{{ error }}</p>
        </div>
    </div>
</div>