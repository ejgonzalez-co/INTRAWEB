<!-- Ht Tic Equipment Resume Id Field -->
{{-- <div class="form-group row m-b-15">
    {!! Form::label('ht_tic_equipment_resume_id', trans('Ht Tic Equipment Resume Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-3">
        {!! Form::number('ht_tic_equipment_resume_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_equipment_resume_id }", 'v-model' => 'dataForm.ht_tic_equipment_resume_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Equipment Resume Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_equipment_resume_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_equipment_resume_id">@{{ error }}</p>
        </div>
    </div>
</div> --}}

<!-- Contract Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_number', trans('Contract Number').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        {!! Form::text('contract_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_number }", 'v-model' => 'dataForm.contract_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Contract Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.contract_number">
            <p class="m-b-0" v-for="error in dataErrors.contract_number">@{{ error }}</p>
        </div>
    </div>

    {!! Form::label('date', trans('Date').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        <date-picker
            :value="dataForm"
            name-field="date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.date">
            <p class="m-b-0" v-for="error in dataErrors.date">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Date Field -->
<div class="form-group row m-b-15">
   
</div>


<!-- Provider Field -->
<div class="form-group row m-b-15">
    {!! Form::label('provider', trans('Provider').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        {!! Form::text('provider', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.provider }", 'v-model' => 'dataForm.provider', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Provider')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.provider">
            <p class="m-b-0" v-for="error in dataErrors.provider">@{{ error }}</p>
        </div>
    </div>

    {!! Form::label('warranty_in_years', trans('Waranty years').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        {!! Form::text('warranty_in_years', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.warranty_in_years }", 'v-model' => 'dataForm.warranty_in_years', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Waranty years')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.warranty_in_years">
            <p class="m-b-0" v-for="error in dataErrors.warranty_in_years">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Contract Total Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_total_value', trans('Contract Total Value').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        {!! Form::text('contract_total_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_total_value }", 'v-model' => 'dataForm.contract_total_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Contract Total Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.contract_total_value">
            <p class="m-b-0" v-for="error in dataErrors.contract_total_value">@{{ error }}</p>
        </div>
    </div>

    {!! Form::label('warranty_termination_date', trans('Waranty Termination Date').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        <date-picker
        :value="dataForm"
        name-field="warranty_termination_date"
        :input-props="{required: true}"
    >
    </date-picker>
        {{-- {!! Form::text('warranty_termination_date', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.warranty_termination_date }", 'v-model' => 'dataForm.warranty_termination_date', 'required' => true]) !!} --}}
        <small>@lang('Enter the') @{{ `@lang('Waranty Termination Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.warranty_termination_date">
            <p class="m-b-0" v-for="error in dataErrors.warranty_termination_date">@{{ error }}</p>
        </div>
    </div>
    {{-- {!! Form::label('status', trans('Status').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        {!! Form::text('status', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.status }", 'v-model' => 'dataForm.status', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Status')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.status">
            <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
        </div>
    </div> --}}
</div>
