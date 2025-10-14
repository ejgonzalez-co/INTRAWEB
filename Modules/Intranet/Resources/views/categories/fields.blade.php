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

<!-- Slug Field -->
{{-- <div class="form-group row m-b-15">
    {!! Form::label('slug', trans('Slug').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('slug', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.slug }", 'v-model' => 'dataForm.slug', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Slug')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.slug">
            <p class="m-b-0" v-for="error in dataErrors.slug">@{{ error }}</p>
        </div>
    </div>
</div> --}}
