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

<!-- Action Field -->
<div class="form-group row m-b-15">
    {!! Form::label('action', trans('Action').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('action', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.action }", 'v-model' => 'dataForm.action', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Action')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.action">
            <p class="m-b-0" v-for="error in dataErrors.action">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
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

<!-- Dependencia Field -->
<div class="form-group row m-b-15">
    {!! Form::label('dependencia', trans('Dependencia').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('dependencia', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.dependencia }", 'v-model' => 'dataForm.dependencia', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Dependencia')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.dependencia">
            <p class="m-b-0" v-for="error in dataErrors.dependencia">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Id Equipment Minor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('id_equipment_minor', trans('Id Equipment Minor').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('id_equipment_minor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.id_equipment_minor }", 'v-model' => 'dataForm.id_equipment_minor', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Id Equipment Minor')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_equipment_minor">
            <p class="m-b-0" v-for="error in dataErrors.id_equipment_minor">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Fuel Equipment Consumption Field -->
<div class="form-group row m-b-15">
    {!! Form::label('fuel_equipment_consumption', trans('Fuel Equipment Consumption').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('fuel_equipment_consumption', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fuel_equipment_consumption }", 'v-model' => 'dataForm.fuel_equipment_consumption', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Fuel Equipment Consumption')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.fuel_equipment_consumption">
            <p class="m-b-0" v-for="error in dataErrors.fuel_equipment_consumption">@{{ error }}</p>
        </div>
    </div>
</div>
