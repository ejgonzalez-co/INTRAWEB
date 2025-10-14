<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description']) !!}
        <small>@lang('Enter the') la @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  State Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}

    <div class="col-md-9">
        <select-check
            css-class="form-control"
            name-field="state"
            reduce-label="name"
            reduce-key="id"
            name-resource="get-constants/type_event_status"
            :value="dataForm"
            :is-required="true">
        </select-check>
        <small>@lang('Select the') el @{{ `@lang('State')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.state">
            <p class="m-b-0" v-for="error in dataErrors.state">@{{ error }}</p>
        </div>
    </div>
</div>

