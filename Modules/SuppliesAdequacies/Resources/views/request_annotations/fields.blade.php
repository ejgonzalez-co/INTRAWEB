<!-- Requests Supplies Adjustements Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('requests_supplies_adjustements_id', trans('Requests Supplies Adjustements Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('requests_supplies_adjustements_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.requests_supplies_adjustements_id }", 'v-model' => 'dataForm.requests_supplies_adjustements_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Requests Supplies Adjustements Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.requests_supplies_adjustements_id">
            <p class="m-b-0" v-for="error in dataErrors.requests_supplies_adjustements_id">{{ error }}</p>
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
            <p class="m-b-0" v-for="error in dataErrors.users_id">{{ error }}</p>
        </div>
    </div>
</div>

<!-- Content Field -->
<div class="form-group row m-b-15">
    {!! Form::label('content', trans('Content').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('content', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.content }", 'v-model' => 'dataForm.content', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Content')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.content">
            <p class="m-b-0" v-for="error in dataErrors.content">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Leido Por Field -->
<div class="form-group row m-b-15">
    {!! Form::label('leido_por', trans('Leido Por').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('leido_por', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.leido_por }", 'v-model' => 'dataForm.leido_por', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Leido Por')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.leido_por">
            <p class="m-b-0" v-for="error in dataErrors.leido_por">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Attached Field -->
<div class="form-group row m-b-15">
    {!! Form::label('attached', trans('Attached').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('attached', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.attached }", 'v-model' => 'dataForm.attached', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Attached')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.attached">
            <p class="m-b-0" v-for="error in dataErrors.attached">@{{ error }}</p>
        </div>
    </div>
</div>