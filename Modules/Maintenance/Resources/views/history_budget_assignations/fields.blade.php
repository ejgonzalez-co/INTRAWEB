<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation">
            <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Name User Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_user', trans('Name User').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name_user', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_user }", 'v-model' => 'dataForm.name_user', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name User')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name_user">
            <p class="m-b-0" v-for="error in dataErrors.name_user">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Value Cdp Field -->
<div class="form-group row m-b-15">
    {!! Form::label('value_cdp', trans('Value Cdp').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('value_cdp', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.value_cdp }", 'v-model' => 'dataForm.value_cdp', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Value Cdp')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.value_cdp">
            <p class="m-b-0" v-for="error in dataErrors.value_cdp">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Value Contract Field -->
<div class="form-group row m-b-15">
    {!! Form::label('value_contract', trans('Value Contract').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('value_contract', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.value_contract }", 'v-model' => 'dataForm.value_contract', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Value Contract')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.value_contract">
            <p class="m-b-0" v-for="error in dataErrors.value_contract">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Cdp Avaible Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cdp_avaible', trans('Cdp Avaible').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('cdp_avaible', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cdp_avaible }", 'v-model' => 'dataForm.cdp_avaible', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Cdp Avaible')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cdp_avaible">
            <p class="m-b-0" v-for="error in dataErrors.cdp_avaible">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Users Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', trans('Users Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('users_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.users_id }", 'v-model' => 'dataForm.users_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Users Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.users_id">
            <p class="m-b-0" v-for="error in dataErrors.users_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mant Provider Contract Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mant_provider_contract_id', trans('Mant Provider Contract Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('mant_provider_contract_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mant_provider_contract_id }", 'v-model' => 'dataForm.mant_provider_contract_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mant Provider Contract Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mant_provider_contract_id">
            <p class="m-b-0" v-for="error in dataErrors.mant_provider_contract_id">@{{ error }}</p>
        </div>
    </div>
</div>
