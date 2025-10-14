<!-- Id Type Documentaries Field -->
{{-- <div class="form-group row m-b-15">
    {!! Form::label('id_type_documentaries', trans('Id Type Documentaries').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('id_type_documentaries', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.id_type_documentaries }", 'v-model' => 'dataForm.id_type_documentaries', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Id Type Documentaries')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_type_documentaries">
            <p class="m-b-0" v-for="error in dataErrors.id_type_documentaries">@{{ error }}</p>
        </div>
    </div>
</div> --}}

<!-- Id Series Subseries Field -->
{{-- <div class="form-group row m-b-15">
    {!! Form::label('id_series_subseries', trans('Id Series Subseries').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('id_series_subseries', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.id_series_subseries }", 'v-model' => 'dataForm.id_series_subseries', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Id Series Subseries')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_series_subseries">
            <p class="m-b-0" v-for="error in dataErrors.id_series_subseries">@{{ error }}</p>
        </div>
    </div>
</div> --}}

{{-- select typeDocumentaries --}}
<div class="form-group row m-b-15">
    {!! Form::label('typeDocumentaries', trans('tipo documental').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <select-check
                css-class="form-control"
                name-field="id_type_documentaries"
                reduce-label="name"
                reduce-key="id"
                name-resource="get-typeDocumentaries"
                :value="dataForm"
                :is-requered="true">
            </select-check>
            <small>@lang('Select the') @{{ 'nombre del tipo de documento.' | lowercase }}</small>
            <div class="invalid-feedback" v-if="dataErrors.isForAllCitizens">
                <p class="m-b-0" v-for="error in dataErrors.isForAllCitizens">@{{ error }}</p>
            </div>
    </div>
</div>
