<!-- Title Field -->
<div class="form-group row m-b-15">
    {!! Form::label('title', trans('Title').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.title }", 'v-model' => 'dataForm.title', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Title')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.title">
            <p class="m-b-0" v-for="error in dataErrors.title">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Alias Field -->
{{-- <div class="form-group row m-b-15">
    {!! Form::label('alias', trans('Alias').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('alias', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.alias }", 'v-model' => 'dataForm.alias']) !!}
        <small>@lang('Enter the') @{{ `@lang('Alias')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.alias">
            <p class="m-b-0" v-for="error in dataErrors.alias">@{{ error }}</p>
        </div>
    </div>
</div> --}}

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Contenido de la licencia').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description']) !!}
        <small>@lang('Enter the') @{{ `@lang('contenido de la licencia')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Published Field -->
<div class="form-group row m-b-15">
    {!! Form::label('published', trans('Publicado').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <!-- published switcher -->
    <div class="switcher col-md-9">
        {!! Form::select('published', ["Publicado" => "Publicado", "Despublicado" => "Despublicado"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.published }", 'v-model' => 'dataForm.published', 'required' => true]) !!}
        <small>@lang('Select the') @{{ `@lang('estado')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.published">
            <p class="m-b-0" v-for="error in dataErrors.published">@{{ error }}</p>
        </div>
    </div>
</div>