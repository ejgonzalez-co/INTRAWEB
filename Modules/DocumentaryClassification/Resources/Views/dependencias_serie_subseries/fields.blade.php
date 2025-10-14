<!-- Id Dependecias Field -->
<div class="form-group row m-b-15">
    {!! Form::label('id_dependencia', trans('Id Dependecias').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('id_dependencia', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.id_dependencia}", 'v-model' => 'dataForm.id_dependencia', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Id Dependecias')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_dependencia">
            <p class="m-b-0" v-for="error in dataErrors.id_dependencia">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Id Series Subseries Field -->
<div class="form-group row m-b-15">
    {!! Form::label('id_series_subseries', trans('Id Series Subseries').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('id_series_subseries', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.id_series_subseries }", 'v-model' => 'dataForm.id_series_subseries', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Id Series Subseries')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_series_subseries">
            <p class="m-b-0" v-for="error in dataErrors.id_series_subseries">@{{ error }}</p>
        </div>
    </div>
</div>
