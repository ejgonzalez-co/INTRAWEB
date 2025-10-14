<!-- Registration Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('registration_date', trans('Registration Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="registration_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Registration Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.registration_date">
            <p class="m-b-0" v-for="error in dataErrors.registration_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Tire Reference Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mant_tire_all_id', trans('Tire Reference') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check
            css-class="form-control"
            name-field="mant_tire_all_id"
            reduce-label="name"
            reduce-key="id"
            name-resource="get-tire-reference"
            :value="dataForm"
            :is-required="true">
        </select-check>        
        <small>@lang('Enter the') @{{ `@lang('Tire Reference')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mant_tire_all_id">
            <p class="m-b-0" v-for="error in dataErrors.mant_tire_all_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Inflation Pressure Field -->
<div class="form-group row m-b-15">
    {!! Form::label('inflation_pressure', trans('inflationPressures').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <currency-input
                v-model="dataForm.inflation_pressure"
                required="true"
                :currency="{'suffix': ''}"
                locale="es"
                :precision="2"
                class="form-control"
                :key="keyRefresh"                    
                >
        </currency-input>
        <small>@lang('Enter the') @{{ `@lang('inflationPressures')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.inflation_pressure">
            <p class="m-b-0" v-for="error in dataErrors.inflation_pressure">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => false]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation">
            <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
        </div>
    </div>
</div>
