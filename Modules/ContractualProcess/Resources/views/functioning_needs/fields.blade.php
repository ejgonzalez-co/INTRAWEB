<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') la @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Estimated Total Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('estimated_total_value', trans('Estimated Total Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <currency-input
            v-model="dataForm.estimated_total_value"
            required="true"
            :currency="{'prefix': '$ '}"
            locale="es"
            class="form-control"
            :key="keyRefresh"
            >
        </currency-input>
        <small>@lang('Enter the') el @{{ `@lang('Estimated Total Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.estimated_total_value">
            <p class="m-b-0" v-for="error in dataErrors.estimated_total_value">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation']) !!}
        <small>@lang('Enter the') la @{{ `@lang('Observation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation">
            <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
        </div>
    </div>
</div>
