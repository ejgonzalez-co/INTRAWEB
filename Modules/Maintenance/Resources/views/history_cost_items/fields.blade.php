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

<!-- Name Cost Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_cost', trans('Name Cost').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name_cost', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_cost }", 'v-model' => 'dataForm.name_cost', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name Cost')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name_cost">
            <p class="m-b-0" v-for="error in dataErrors.name_cost">@{{ error }}</p>
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

<!-- Code Cost Field -->
<div class="form-group row m-b-15">
    {!! Form::label('code_cost', trans('Code Cost').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('code_cost', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.code_cost }", 'v-model' => 'dataForm.code_cost', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Code Cost')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.code_cost">
            <p class="m-b-0" v-for="error in dataErrors.code_cost">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Cost Center Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cost_center', trans('Cost Center').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('cost_center', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cost_center }", 'v-model' => 'dataForm.cost_center', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Cost Center')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cost_center">
            <p class="m-b-0" v-for="error in dataErrors.cost_center">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Cost Center Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cost_center_name', trans('Cost Center Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('cost_center_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cost_center_name }", 'v-model' => 'dataForm.cost_center_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Cost Center Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cost_center_name">
            <p class="m-b-0" v-for="error in dataErrors.cost_center_name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Value Item Field -->
<div class="form-group row m-b-15">
    {!! Form::label('value_item', trans('Value Item').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('value_item', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.value_item }", 'v-model' => 'dataForm.value_item', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Value Item')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.value_item">
            <p class="m-b-0" v-for="error in dataErrors.value_item">@{{ error }}</p>
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

<!-- Mant Budget Assignation Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mant_budget_assignation_id', trans('Mant Budget Assignation Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('mant_budget_assignation_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mant_budget_assignation_id }", 'v-model' => 'dataForm.mant_budget_assignation_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mant Budget Assignation Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mant_budget_assignation_id">
            <p class="m-b-0" v-for="error in dataErrors.mant_budget_assignation_id">@{{ error }}</p>
        </div>
    </div>
</div>
