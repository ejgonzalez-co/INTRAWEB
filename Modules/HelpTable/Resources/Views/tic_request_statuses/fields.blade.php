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

<!-- Status Color Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status_color', trans('Status Color').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <colour-picker
            :value="dataForm"
            name-field="status_color"
        >
        </colour-picker>
        <small>@lang('Enter the') @{{ `@lang('Status Color')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.status_color">
            <p class="m-b-0" v-for="error in dataErrors.status_color">@{{ error }}</p>
        </div>
    </div>
</div>
