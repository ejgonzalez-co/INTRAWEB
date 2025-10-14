<!-- Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type', trans('Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('type', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.type }", 'v-model' => 'dataForm.type', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Type')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.type">
            <p class="m-b-0" v-for="error in dataErrors.type">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Number List Field -->
<div class="form-group row m-b-15">
    {!! Form::label('number_list', trans('Number List').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('number_list', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.number_list }", 'v-model' => 'dataForm.number_list', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Number List')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.number_list">
            <p class="m-b-0" v-for="error in dataErrors.number_list">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Code Field -->
<div class="form-group row m-b-15">
    {!! Form::label('code', trans('Code').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('code', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.code }", 'v-model' => 'dataForm.code', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Code')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.code">
            <p class="m-b-0" v-for="error in dataErrors.code">@{{ error }}</p>
        </div>
    </div>
</div>

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

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>
