<!-- Pc Paa Calls Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_paa_calls_id', trans('Pc Paa Calls Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_paa_calls_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pc_paa_calls_id }", 'v-model' => 'dataForm.pc_paa_calls_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pc Paa Calls Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pc_paa_calls_id">
            <p class="m-b-0" v-for="error in dataErrors.pc_paa_calls_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Pc Process Leaders Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_process_leaders_id', trans('Pc Process Leaders Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_process_leaders_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pc_process_leaders_id }", 'v-model' => 'dataForm.pc_process_leaders_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pc Process Leaders Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pc_process_leaders_id">
            <p class="m-b-0" v-for="error in dataErrors.pc_process_leaders_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Name Process Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_process', trans('Name Process').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name_process', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_process }", 'v-model' => 'dataForm.name_process', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name Process')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name_process">
            <p class="m-b-0" v-for="error in dataErrors.name_process">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  State Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- state switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="state" id="state"  v-model="dataForm.state">
        <label for="state"></label>
        <small>@lang('Select the') @{{ `@lang('State')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.state">
            <p class="m-b-0" v-for="error in dataErrors.state">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Total Value Paa Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_value_paa', trans('Total Value Paa').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('total_value_paa', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.total_value_paa }", 'v-model' => 'dataForm.total_value_paa', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Total Value Paa')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.total_value_paa">
            <p class="m-b-0" v-for="error in dataErrors.total_value_paa">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Total Operating Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_operating_value', trans('Total Operating Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('total_operating_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.total_operating_value }", 'v-model' => 'dataForm.total_operating_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Total Operating Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.total_operating_value">
            <p class="m-b-0" v-for="error in dataErrors.total_operating_value">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Future Validity Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('future_validity_status', trans('Future Validity Status').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- future_validity_status switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="future_validity_status" id="future_validity_status"  v-model="dataForm.future_validity_status">
        <label for="future_validity_status"></label>
        <small>@lang('Select the') @{{ `@lang('Future Validity Status')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.future_validity_status">
            <p class="m-b-0" v-for="error in dataErrors.future_validity_status">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Total Investment Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_investment_value', trans('Total Investment Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('total_investment_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.total_investment_value }", 'v-model' => 'dataForm.total_investment_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Total Investment Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.total_investment_value">
            <p class="m-b-0" v-for="error in dataErrors.total_investment_value">@{{ error }}</p>
        </div>
    </div>
</div>
