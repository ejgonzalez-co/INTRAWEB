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

<!-- Cd Avaible Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cd_avaible', trans('Cd Avaible').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('cd_avaible', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cd_avaible }", 'v-model' => 'dataForm.cd_avaible', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Cd Avaible')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cd_avaible">
            <p class="m-b-0" v-for="error in dataErrors.cd_avaible">@{{ error }}</p>
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

<!-- Object Field -->
<div class="form-group row m-b-15">
    {!! Form::label('object', trans('Object').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('object', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.object }", 'v-model' => 'dataForm.object', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Object')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.object">
            <p class="m-b-0" v-for="error in dataErrors.object">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Provider Field -->
<div class="form-group row m-b-15">
    {!! Form::label('provider', trans('Provider').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('provider', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.provider }", 'v-model' => 'dataForm.provider', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Provider')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.provider">
            <p class="m-b-0" v-for="error in dataErrors.provider">@{{ error }}</p>
        </div>
    </div>
</div>
